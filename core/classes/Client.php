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
        try {
            echo "客户端启动成功\r\n";
            global $conf;
            Timer::add(3, function (){
                file_put_contents('isRunning', time());
            });
            ChannelClient::connect($conf['channel_ip'], $conf['channel_port']);
            ChannelClient::on(EVENT_OUT_CONNECT .$conf['data_bus'], function ($data) use ($conf){
                $channel = $data[CHANNEL];
                self::$localServer[$channel] = new LinkLocalServer($data[URI]);
                self::$localServer[$channel]->initChannel($conf['data_bus'], $channel)->connect();
            });
            ChannelClient::on(EVENT_OUT_MSG . $conf['data_bus'], function ($data) use ($conf){
                $channel = $data[CHANNEL];
                if (isset(self::$localServer[$channel])) {
                    self::$localServer[$channel]->send(base64_decode($data[DATA]));
                }
            });
            ChannelClient::on(EVENT_IN_CLOSE . $conf['data_bus'], function ($channel)  {
                if (isset(self::$localServer[$channel])) {
                    unset(self::$localServer[$channel]);
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