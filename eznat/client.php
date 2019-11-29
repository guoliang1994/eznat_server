<?php
require __DIR__ . "/../vendor/autoload.php";

use core\classes\Client;
use core\classes\Server;
use core\conf\Conf;

global $conf;
$conf = (new Conf())->conf;

$time = file_get_contents('isRunning');
var_dump($time);
var_dump(time() - $time );
if (time() - $time <= $conf['keep_alive']) {
    die("已经启动了");
}
$client = new Client();
$client::runAll();
