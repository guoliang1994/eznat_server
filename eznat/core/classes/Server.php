<?php
namespace core\classes;

use App\Model\Client;
use App\Model\PortMap;
use App\Model\WebMap;
use Channel\Client as ChannelClient;
use core\interfaces\WorkerInterface;
use Workerman\Lib\Timer;

class Server extends WorkerWithCallback implements WorkerInterface
{
    /**
     * @param string $socket_name
     * @param array $context_option
     */
    private static $inConnectionChannel = [];
    private static $inClientList = [];
    public function __construct($socket_name = '', $context_option = array())
    {
        parent::__construct($socket_name, $context_option);
    }

    function onMessage($connection, $data)
    {
        $connection->lastMsgTime = time();
        if ($connection->isWeb) {
            preg_match("/Host:\s.*/i", serialize($data), $match);
            if (empty($match)) {
                return;
            }
            $domain = preg_replace("/host:\s*/i", "", str_replace("\r", "", $match[0]));
            $webMap = new WebMap();
            $mapInfo = $webMap->joinRelationTable()
                ->notFrozen()
                ->where('domain', $domain)
                ->first();
            // 如果存在web映射，获取数据传输通道
            if ($mapInfo && isset(self::$inClientList[$mapInfo->data_bus])) {
                $this->setInMsgListen($connection, $mapInfo);
                $connectData['map_info'] = $connection->mapInfo;
                $connectData['channel'] = $connection->channel;
                ChannelClient::publish("EV_OUT_CONNECT" . $connection->dataBus, $connectData);
            } else {
                $connection->close();
                return;
            }
        }
        $send['data'] = $data;
        $send['map_info'] = $connection->mapInfo;
        $send['channel'] = $connection->channel;
        ChannelClient::publish("EV_OUT_MSG" . $connection->dataBus, $send);
    }
    private function setInMsgListen(&$connection, $mapInfo)
    {
        $dataBus = $mapInfo->client->data_bus;
        $connection->dataBus = $dataBus;
        $connection->mapInfo = $mapInfo->toArray();
        $connection->channel = $dataBus . $mapInfo->local_ip . $mapInfo->local_port . rand(1, 100);
        self::$inConnectionChannel[$connection->channel] = $connection;
        ChannelClient::on("EV_IN_MSG" . $dataBus, function ($data) use ($connection){
            // 本地传回数据
            if (isset(self::$inConnectionChannel[$data['channel']])) {
                self::$inConnectionChannel[$data['channel']]->send($data['data']);
            }
        });
        ChannelClient::on("EV_IN_CLOSE" . $dataBus, function ($channel) use ($connection){
            if (isset(self::$inConnectionChannel[$channel])) {
                self::$inConnectionChannel[$channel]->close();
                unset(self::$inConnectionChannel[$channel]);
            }
        });
    }
    // 一个内部穿透对应多个外部连接
    function onConnect($connection)
    {
        $connection->isWeb = false;
        $connection->uniqid = uniqid(). rand(100, 999);

        $remotePort = $connection->getLocalPort();
        if ($remotePort == 80 || $remotePort == 443) {
            $connection->isWeb = true;
            return;
        }

        $mapInfo = PortMap::where('remote_port', $remotePort)->first();
        if (!empty($mapInfo)) {
            $this->setInMsgListen($connection, $mapInfo);
            $connectData['map_info'] = $connection->mapInfo;
            $connectData['channel'] = $connection->channel;
            ChannelClient::publish("EV_OUT_CONNECT" .$connection->dataBus, $connectData);
        } else {
            $connection->close();
            return;
        }
    }
    /*
     * 外部连接，有两种关闭方式。1，主动关闭，2超时关闭
     * 如果是外部连接主动关闭，则会调用此方法。
     * */
    function onClose($connection)
    {
        if (isset($connection->channel)) {
            unset(self::$inConnectionChannel[$connection->channel]);
        }
    }

    function onError($connection, $code, $msg){
        self::log($msg);
    }

    function onWorkerStart($worker)
    {
        ChannelClient::connect("10.20.1.80", CHANNEL_PORT);
        // 客户端上线
        ChannelClient::on("EV_IN_CLIENT_ONLINE", function ($client) {
            if (!isset(self::$inClientList[$client['data_bus']])) {
                $register = [
                    'time' => time(),
                    'key' => uniqid()
                ];
                Client::where('data_bus', '=', $client['data_bus'])->update(['is_online' => 1]);
                self::$inClientList[$client['data_bus']] = $register;
            } else {
                self::$inClientList[$client['data_bus']]['time'] = time();
            }
        });
        // 客户端在线监测
        Timer::add(3, function (){
            foreach (self::$inClientList as $dataBus => $client) {
                if (time() - $client['time'] > 3) {
                    Client::where('data_bus', '=', $dataBus)->update(['is_online' => 0]);
                    unset(self::$inClientList[$dataBus]);
                }
            }
        });
    }

    function onWorkerStop($worker){}
    function onWorkerReload($worker){}
    function onBufferFull($connection){}
    function onBufferDrain($connection){}
}
