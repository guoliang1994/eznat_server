<?php
namespace core\classes;

use Channel\Client as ChannelClient;
use core\interfaces\WorkerInterface;
use Workerman\Lib\Timer;
use function foo\func;

class Server extends WorkerWithCallback implements WorkerInterface
{
    /**
     * @param string $socket_name
     * @param array $context_option
     */
    private static  $outConnectionList = [];
    private static $inClientList = [];
    public function __construct($socket_name = '', $context_option = array())
    {
        parent::__construct($socket_name, $context_option);
    }

    function onMessage($connection, $data)
    {
        $send['data'] = $data;
        $send['channel'] = $connection->channel;
        ChannelClient::publish("OUT_MSG", $send);
    }

    function onConnect($connection)
    {
        global $db;
        echo "\n有新的连接进来";

        $remotePort = $connection->getLocalPort();
        $clientInfo = $db->query("select * from port_map where remote_port = {$remotePort} limit 1")->fetch();

        $connection->channelKey = $clientInfo['channel'];
        $connection->mapLocalPort =  $clientInfo['local_port'];
        $connection->mapLocalIp =  $clientInfo['local_ip'];
        $connection->uniquid = uniqid();

        $connection->channel = $connection->channelKey
                             . $connection->mapLocalIp
                             .$connection->mapLocalPort
                             .$connection->uniquid;
        if (!isset(self::$inClientList[$clientInfo['channel']])) {
            echo "客户端不在线上";
            $connection->close();
            return;
        }
        self::$outConnectionList[$connection->channel] = $connection; // 将连接存储到静态变量
        ChannelClient::on("IN_MSG", function ($data) use ($connection){
            // 本地传回数据
            if (isset(self::$outConnectionList[$data['channel']])) {
                self::$outConnectionList[$data['channel']]->send($data['data']);
            }
        });
        ChannelClient::on("IN_CLOSE", function ($data) use ($connection){
            if (isset(self::$outConnectionList[$data])) {
                self::$outConnectionList[$data]->close();
            }
            unset(self::$outConnectionList[$data]);
        });
        ChannelClient::publish("OUT_CONNECT", $connection);
    }

    function onClose($connection)
    {
        echo "关闭连接";
        $send['channel'] = $connection->channel;
        ChannelClient::publish("OUT_CLOSE", $send);
        unset(self::$outConnectionList[$connection->channel]);
    }

    function onError($connection, $code, $msg){
        self::log($msg);
    }

    function onBufferFull($connection){}

    function onBufferDrain($connection){}

    function onWorkerStart($worker)
    {
        global $conf;
        ChannelClient::connect("127.0.0.1", $conf['channel_port']);
        // 内网客户端注册
        ChannelClient::on("IN_REGISTER", function ($channel) {
            global $db;
            if (isset(self::$inClientList[$channel])) {
                // 更新注册时间
                self::$inClientList[$channel] = time();
            } else {
                $clientInfo = $db->query("select * from client where channel = '{$channel}'");
                if ($clientInfo) {
                    self::$inClientList[$channel] = time();
                }
            }
        });
        // 清除断线的客户端连接
        Timer::add(2, function (){
            foreach (self::$inClientList as $channel => $registerTime) {
                if (time() - $registerTime > 3) {
                    unset(self::$inClientList[$channel]);
                }
            }
        });
    }

    function onWorkerStop($worker){}

    function onWorkerReload($worker)
    {
    }
}