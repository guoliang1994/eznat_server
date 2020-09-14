<?php
require __DIR__ . "/../vendor/autoload.php";

use Channel\Client as ChannelClient;
use Workerman\Worker;
use Dotenv\Dotenv;
# 载入 .env中的所有变量
Dotenv::create(__DIR__."/../")->load();

$dataBus = "2d49b68bd35a1784f05b9d931b9f2433";
$worker = new Worker("tcp://0.0.0.0:8889");
global $connections;
$worker->onMessage = function ($con, $data) use ($dataBus){
    ChannelClient::publish("EV_OUT_MSG" . $dataBus, ['channel' => $con->channel, 'data' => base64_encode($data)]);
};

$worker->onConnect = function ($con) use ($dataBus, $worker){
    echo "有连接呀";
    global $connections;
    $channel =  uniqid();
    $con->channel = $channel;
    $connections[$channel] = $con;
    ChannelClient::publish("EV_OUT_CONNECT" . $dataBus, ['map_info' => ['local_ip' => "127.0.0.1", 'local_port' => "3306"], 'channel' =>  $channel]);
    ChannelClient::on("EV_IN_MSG" . $dataBus, function ($data) use ($con, $worker){
            global $connections;
            foreach ($connections as  $connection) {
                if ($connection->channel == $data['channel']) {
                    $connection->send(base64_decode($data['data']));
                }
            }
    });
};
$worker->onClose = function ($con) {
    global $connections;
    $con->close();
    unset($connections[$con->channel]);
};
$worker->onWorkerStart = function ($worker) use ($dataBus){
    ChannelClient::connect(env('CHANNEL_IP'), env('CHANNEL_PORT'));
};
$worker->count = 1;

Worker::runAll();
