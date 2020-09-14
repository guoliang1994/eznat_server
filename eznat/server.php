<?php
require_once 'init_laravel_orm.php';

use Workerman\Worker;
use Channel\Server as ChannelServer;

use core\Manage;
use core\classes\Server;

use App\Model\PortMap;
use App\Model\Client;

Client::where('id', '>', 0)->update(['is_online' => 0]); # 重置客户端状态为下线
PortMap::where('id', '>', 0)->update(['is_online' => 0]); # 重置服务端状态为下线

$data = PortMap::all();
foreach ($data as $port) {
    if ($port['remote_port'] == env("HTTP_MAP_SERVER_PORT") || $port['remote_port'] == env("HTTPS_MAP_SERVER_PORT")) {
        continue;
    }
    Manage::generateScriptFile($port);
}

$web = new Server("tcp://0.0.0.0:" . env("HTTP_MAP_SERVER_PORT"));
$httpsWeb = new Server("tcp://0.0.0.0:" .env("HTTPS_MAP_SERVER_PORT") );
$web->name = "web";
$httpsWeb->name = "https_web";

Worker::runAll();
