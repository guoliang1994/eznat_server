<?php
$time = file_get_contents('isRunning');

if (time() - $time > 5) {
    echo "重启";
    exec("..\\runenv\\win_php\\php.exe client.php", $out);
    var_dump($out);
}