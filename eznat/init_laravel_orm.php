<?php
require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

# 载入 .env中的所有变量
Dotenv::create(__DIR__."/../")->load();

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$configPath =  include_once $app->configPath() . "/database.php";
$conf = new Illuminate\Config\Repository($configPath);
$databaseConfig = $conf->get("connections.mysql");
$databaseConfig = array_merge($databaseConfig,  ['options' => [PDO::ATTR_EMULATE_PREPARES => true, PDO::ATTR_PERSISTENT => true]]);
$capsule = new Capsule;
$capsule->addConnection($databaseConfig);
$capsule->setAsGlobal();
$capsule->bootEloquent();
