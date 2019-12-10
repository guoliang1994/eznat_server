<?php
namespace core\classes;

use Channel\Client as ChannelClient;
use core\interfaces\WorkerInterface;
use Workerman\Lib\Timer;

class Client extends WorkerWithCallback implements WorkerInterface
{
    private static $localServer = [];
    function onMessage($connection, $data){}

    function onClose($connection){}

    function onError($connection, $code, $msg){}

    function onBufferFull($connection){}

    function onBufferDrain($connection){}

    function onWorkerStart($worker)
    {
        echo "本地worker启动\r\n";
        try {
            global $conf;
            ChannelClient::connect($conf['channel_ip'], $conf['channel_port']);
            Timer::add(3,function() use ($conf){
                ChannelClient::publish("IN_REGISTER", $conf['channel']);
            });
            
            file_put_contents("isRunning", time());
            Timer::add(ceil($conf['keep_alive']/2), function(){
                file_put_contents("isRunning", time());
            });
            ChannelClient::on("OUT_CONNECT" .$conf['channel'], function ($outConnection){
                $channel = $outConnection->channel;
                self::$localServer[$channel] = new LinkLocalServer($outConnection->mapLocalIp.":".$outConnection->mapLocalPort);
                self::$localServer[$channel]->channel = $channel;
                self::$localServer[$channel]->connect();
            });
            ChannelClient::on("OUT_MSG", function ($data) {
                if (isset(self::$localServer[$data['channel']])) {
                    self::$localServer[$data['channel']]->send($data['data']);
                }
            });
            ChannelClient::on("OUT_CLOSE", function ($channel) {
                if (isset(self::$localServer[$channel])) {
                    self::$localServer[$channel]->close();
                    unset(self::$localServer[$channel]); // 销毁开启的连接
                }
            });
        } catch (\Exception $e) {
            echo "远程隧道连接失败";
        }
    }

    function onWorkerStop($worker)
    {
    }

    function onWorkerReload($worker)
    {
        // TODO: Implement onWorkerReload() method.
    }
    function onConnect($connection){}
}