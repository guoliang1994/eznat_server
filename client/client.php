<?php
require __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . '/../common/event_const.php';

use core\classes\Client;

global $conf;
$conf = parse_ini_file('conf/conf.ini');

# 因为windows不支持信号，所以使用定时器写文件来判断是否正在运行。
# linux 直接定时任务运行这个文件就行，因为不会重复启动多个
if (DIRECTORY_SEPARATOR == '\\') {
    $time = file_get_contents('isRunning');
    if (time() - $time <= 1.5) {
        die('客户端已经启动');
    }
}

$client = new Client();
$client::runAll();