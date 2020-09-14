<?php
require __DIR__ . "/../vendor/autoload.php";
use Channel\Server as ChannelServer;
use Workerman\Worker;
use Dotenv\Dotenv;
# 载入 .env中的所有变量
Dotenv::create(__DIR__."/../")->load();

$channelServer = new ChannelServer("0.0.0.0", env('CHANNEL_PORT'));

Worker::runAll();
