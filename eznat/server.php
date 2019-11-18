<?php
require __DIR__ . "/../vendor/autoload.php";

use Workerman\Worker;
use Workerman\WebServer;
use Channel\Server as ChannelServer;
use core\classes\Server;
use core\conf\Conf;

global $conf;
global $db;
$conf = (new Conf())->conf;

$channelServer = new ChannelServer("0.0.0.0", $conf['channel_port']);

$db = new PDO("sqlite:".__DIR__."/data/eznat.db");
$data = $db->query('SELECT * FROM port_map');
foreach ($data as $port) {
    $server = new Server("tcp://0.0.0.0:" . $port['remote_port']);
    $server->name = $port['channel'];
}

Worker::runAll();
