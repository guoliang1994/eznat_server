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
            $connection->data .= $data;
            preg_match("/Content-Length:\s*\d*/i", $connection->data, $paramsLength);
            $headerEnd = \strpos($connection->data, "\r\n\r\n");
            $headerLength = strlen(substr($connection->data, 0, $headerEnd));
            if (!empty($paramsLength)) {
                $connection->totalContentLength = (int)str_replace("Content-Length: ", '', $paramsLength[0]);
                echo "总的数据包长度：{ $connection->totalContentLength }";
                $connection->receveContentLength += strlen($data);
                echo "接收到的数据包长度： " . ($connection->receveContentLength - $headerLength - 4);
                if ($connection->totalContentLength > $connection->receveContentLength) {
                    echo "\r\n数据包不完整，继续接受数据";
                    return;
                }
            }
            $data = $connection->data;
            preg_match("/Host:\s.*/i", serialize($connection->data), $match);
            if (empty($match)) {
                $connection->close();
                return;
            }
            $domain = explode(':', preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)/", '', $match[0]));
            $webMap = new WebMap();
            $mapInfo = $webMap
                ->with(['client' => function ($query) {
                    $query->select('id', 'data_bus');
                }])
                ->whereHas('client.user', function ($user) {
                    $user->notFrozen();
                })
                ->where('domain', $domain[1])
                ->select('domain', 'local_ip', 'local_port', 'client_id')
                ->first();
            // 如果存在web映射，获取数据传输通道
            if ($mapInfo && isset(self::$inClientList[$mapInfo->client->data_bus])) {
                $this->setInMsgListen($connection, $mapInfo);
                $connectData['map_info'] = $connection->mapInfo;
                $connectData['channel'] = $connection->channel;
                ChannelClient::publish("EV_OUT_CONNECT" . $connection->dataBus, $connectData);
            } else {
                // die('站点错误');
                $connection->close();
                return;
            }
        }
        $this->recordIOFlow($connection, "i", strlen($data));
        $send['data'] = base64_encode($data);
        $send['map_info'] = $connection->mapInfo;
        $send['channel'] = $connection->channel;
        ChannelClient::publish("EV_OUT_MSG" . $connection->dataBus, $send);

        $connection->receveContentLength = 0;
        $connection->totalContentLength = 0;
        $connection->data = "";
    }

    private function setInMsgListen(&$connection, $mapInfo)
    {
        $dataBus = $mapInfo->client->data_bus;
        $connection->dataBus = $dataBus;
        $connection->mapInfo = $mapInfo->toArray();
        $connection->channel = $dataBus . $mapInfo->local_ip . $mapInfo->local_port . "_" . uniqid("_", true);
        self::$inConnectionChannel[$connection->channel] = $connection;
        ChannelClient::on("EV_IN_MSG" . $dataBus, function ($data) use ($connection) {
            // 本地传回数据
            if (isset(self::$inConnectionChannel[$data['channel']])) {
                $this->recordIOFlow($connection, "o", strlen($data['data']));
                self::$inConnectionChannel[$data['channel']]->send(base64_decode($data['data']));
            }
        });
        ChannelClient::on("EV_IN_CLOSE" . $dataBus, function ($channel) use ($connection) {
            if (isset(self::$inConnectionChannel[$channel])) {
                self::$inConnectionChannel[$channel]->close();
                unset(self::$inConnectionChannel[$channel]);
            }
        });
    }

    protected function recordIOFlow($connection, $type, $length)
    {
        if ($connection->isWeb) {
            WebMap::where('domain', $connection->mapInfo['domain'])->increment($type, $length);
        } else {
            PortMap::where('remote_port', $connection->getLocalPort())->increment($type, $length);
        }
    }

    // 一个内部穿透对应多个外部连接
    function onConnect($connection)
    {
        echo "外部连接进来";

        $connection->maxSendBufferSize = 50 * 1024 * 1024;
        $connection->maxPackageSize = 50 * 1024 * 1024;
        $connection->isWeb = false;
        $connection->uniqid = uniqid() . rand(100, 999);

        $remotePort = $connection->getLocalPort();
        if ($remotePort == env("HTTP_MAP_SERVER_PORT") || $remotePort == env("HTTPS_MAP_SERVER_PORT")) {
            $connection->isWeb = true;
            $connection->receveContentLength = 0; // tcp会分包
            $connection->totalContentLength = 0; // tcp会分包
            $connection->data = "";
            return;
        }

        $mapInfo = PortMap::where('remote_port', $remotePort)->select('local_ip', 'local_port', 'client_id')->first();
        if (!empty($mapInfo)) {
            $this->setInMsgListen($connection, $mapInfo);
            $connectData['map_info'] = $connection->mapInfo;
            $connectData['channel'] = $connection->channel;
            ChannelClient::publish("EV_OUT_CONNECT" . $connection->dataBus, $connectData);
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
            $data['channel'] = $connection->channel;
            ChannelClient::publish("EV_OUT_CLOSE" . $connection->dataBus, $data);
            unset(self::$inConnectionChannel[$connection->channel]);
        }
    }

    function onError($connection, $code, $msg)
    {
        self::log($msg);
    }

    function onWorkerStart($worker)
    {
        ChannelClient::connect(env('CHANNEL_IP'), env('CHANNEL_PORT'));
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
        Timer::add(5, function () {
            foreach (self::$inClientList as $dataBus => $client) {
                if (time() - $client['time'] > 10) {
                    Client::where('data_bus', '=', $dataBus)->update(['is_online' => 0]);
                    unset(self::$inClientList[$dataBus]);
                }
            }
        });
    }

    function onWorkerStop($worker)
    {
    }

    function onWorkerReload($worker)
    {
    }

    function onBufferFull($connection)
    {
    }

    function onBufferDrain($connection)
    {
    }
}
