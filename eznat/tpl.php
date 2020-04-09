<?php
require_once __DIR__. '/../init_laravel_orm.php';

use Workerman\Worker;
use core\classes\Server;
use App\Model\PortMap;

global $workerListenPort;
$scriptName =  explode('_', __FILE__);
$workerListenPort = str_replace('server/', '', $scriptName[3]);
new Server("tcp://0.0.0.0:" . $workerListenPort);
if ($argv[1] == 'start' || $argv[1] == 'restart') {
    PortMap::where('remote_port', '=', $workerListenPort)->update(['is_online' => 1]);
} else if ($argv[1] == 'stop') {
    PortMap::where('remote_port', '=', $workerListenPort)->update(['is_online' => 0]);
}
Worker::runAll();
