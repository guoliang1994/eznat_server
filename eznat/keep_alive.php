<?php
require __DIR__ . "/../vendor/autoload.php";
use core\conf\Conf;
$conf = (new Conf())->conf;
$times = 0;
while ($times < 60) {
    $time = file_get_contents('isRunning');
    if (time() - $time > $conf['keep_alive']) {
        echo "重启";
        if( DIRECTORY_SEPARATOR=='\\') {
            exec("..\\runenv\\win_php\\php.exe client.php", $out);
            var_dump($out);
        } else {
            exec("php client.php start -d", $out);
            var_dump($out);
        }
    }
    echo "循环{$times}";
    sleep($conf['keep_alive']);
    $times += $conf['keep_alive'];
}
die();
