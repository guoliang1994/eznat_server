<?php
require __DIR__ . "/../vendor/autoload.php";

use core\classes\Client;
use core\conf\Conf;

global $conf;
$conf = (new Conf())->conf;

$client = new Client();
$client::runAll();
