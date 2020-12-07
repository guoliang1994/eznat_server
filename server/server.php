<?php
require __DIR__ . "/../vendor/autoload.php";
require_once __DIR__. '/../common/event_const.php';

use Channel\Server as ChannelServer;
use Workerman\Worker;
use core\classes\Server;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$channelServer = new ChannelServer("0.0.0.0", env('CHANNEL_PORT'));
$mapInfo = file_get_contents(__DIR__ . '/map.json');
$data = json_decode($mapInfo, true);
global $webMap, $portMap;

$webMap = $data['webMap'];
$portMap = $data['portMap'];

foreach ($portMap as $map) {
    $tmpServer = new Server("tcp://0.0.0.0:" . $map['remote_port']);
    $tmpServer->name = @$map['name'] ?: "未命名";
}
$web = new Server("\\core\\Protocols\\MyHttp://0.0.0.0:" . env("HTTP_MAP_SERVER_PORT"));
$web->name = "web";

$httpsDomain = exec("ls  cert | grep key", $keys);
$context = [
    'ssl' => [
        'verify_peer' => false,
        'SNI_enable' => true
    ]
];
foreach ($keys as $index => $key) {
    $domain = str_replace(".key", "", $key);
    $context['ssl']['local_cert'] = "./cert/{$domain}.pem";
    $context['ssl']['local_pk'] = "./cert/{$key}";
    $context['ssl']['SNI_server_certs'][$domain]['local_cert'] = "./cert/{$domain}.pem";
    $context['ssl']['SNI_server_certs'][$domain]['local_pk'] = "./cert/{$key}";
}
$httpsWeb = new Server("\\core\\Protocols\\MyHttp://0.0.0.0:" . env("HTTPS_MAP_SERVER_PORT"), $context);
$httpsWeb->transport = 'ssl';
$httpsWeb->name = "https_web";
Worker::runAll();
