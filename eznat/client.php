<?php
require __DIR__ . "/../vendor/autoload.php";

use core\classes\Client;
use core\conf\Conf;

global $conf;
global $channelKey;
$conf = (new Conf())->conf;

$channelKey = '3027d2c38e4119ad8e5ee53da3f40bd2';
$client = new Client();
$client::runAll();
