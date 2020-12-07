<?php
namespace core\Protocols;

use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;

/**
 * Class Http.
 * @package Workerman\Protocols
 */
class MyHttp extends Http
{
    public static function encode($response, TcpConnection $connection)
    {
        return  (string) $response;
    }
}
