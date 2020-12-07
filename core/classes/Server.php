<?php

namespace core\classes;

use Channel\Client as ChannelClient;
use core\interfaces\WorkerInterface;

class Server extends WorkerWithCallback implements WorkerInterface
{
    private static $inConnectionChannel = [];

    /**
     * @param string $socket_name
     * @param array $context_option
     */
    public function __construct($socket_name = '', $context_option = array())
    {
        parent::__construct($socket_name, $context_option);
    }
    function onMessage($connection, $data)
    {
        $connection->lastMsgTime = time();
        if ($connection->isWeb) {
            $data = $data->rawBuffer();
            preg_match("/Host:\s.*/i", serialize($data), $match);
            if (empty($match)) {
                $connection->close();
                return;
            }
            $domain = explode(':', preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)/", '', $match[0]));
            global $webMap;
            $webMapExists = '';
            foreach ($webMap as $web) {
                if ($web['domain'] == $domain[1]) {
                    $this->setInMsgListen($connection, $web);
                    $connectData = $this->getSendData($connection,  $connection->mapInfo);
                    ChannelClient::publish(EVENT_OUT_CONNECT . $connection->dataBus, $connectData);
                    $webMap = true;
                    break;
                }
            }
            if ($webMapExists) {
                $connection->close();
                return;
            }
        }
        $send = $this->getSendData($connection, $connection->mapInfo, $data);
        ChannelClient::publish(EVENT_OUT_MSG . $connection->dataBus, $send);
    }
    public static function getSendData($connection, Array $mapInfo, $data = "")
    {
        $array = [URI => "{$mapInfo['local_ip']}:{$mapInfo['local_port']}", CHANNEL => $connection->channel];
        if (!empty($data)) {
            $array[DATA] = base64_encode($data);
        }
        return $array;
    }
    private function setInMsgListen(&$connection, array $mapInfo)
    {
        $dataBus = $mapInfo['data_bus'];
        $connection->dataBus = $dataBus;
        $connection->mapInfo = $mapInfo;
        $connection->channel = uniqid();
        self::$inConnectionChannel[$connection->channel] = $connection;
        ChannelClient::on(EVENT_IN_MSG . $dataBus, function ($data) use ($connection, $mapInfo) {
            // 本地传回数据
            if (isset(self::$inConnectionChannel[$data[CHANNEL]])) {
                self::$inConnectionChannel[$data[CHANNEL]]->send(base64_decode($data[DATA]));
            }
        });
        ChannelClient::on(EVENT_IN_CLOSE . $dataBus, function ($channel) use ($connection) {
            if (isset(self::$inConnectionChannel[$channel])) {
                self::$inConnectionChannel[$channel]->close();
                unset(self::$inConnectionChannel[$channel]);
            }
        });
    }
    // 一个内部穿透对应多个外部连接
    function onConnect($connection)
    {
        echo "外部连接进来";
        $connection->maxSendBufferSize = 50 * 1024 * 1024;
        $connection->maxPackageSize = 50 * 1024 * 1024;

        $remotePort = $connection->getLocalPort();
        if ($remotePort == env("HTTP_MAP_SERVER_PORT") || $remotePort == env("HTTPS_MAP_SERVER_PORT")) {
            $connection->isWeb = true;
            if ($remotePort == env("HTTPS_MAP_SERVER_PORT")) {
                $connection->isHttps = true;
            } else {
                $connection->isHttps = false;
            }
            return;
        } else {
            $connection->isWeb = false;

            global $portMap;
            $portMaoExists = false;
            foreach ($portMap as $map) {
                if ($map['remote_port'] == $remotePort) {
                    $this->setInMsgListen($connection, $map);
                    $connectData = $this->getSendData($connection, $connection->mapInfo);
                    ChannelClient::publish(EVENT_OUT_CONNECT . $connection->dataBus, $connectData);
                    $portMaoExists = true;
                    break;
                }
            }
            if (! $portMaoExists) {
                $connection->close();
                return;
            }
        }
    }

    /*
     * 外部连接，有两种关闭方式。1，主动关闭，2超时关闭
     * 如果是外部连接主动关闭，则会调用此方法。
     * */
    function onClose($connection)
    {
        if (isset($connection->channel)) {
            $data[CHANNEL] = $connection->channel;
            ChannelClient::publish(EVENT_OUT_CLOSE . $connection->dataBus, $data);
            unset(self::$inConnectionChannel[$connection->channel]);
        }
    }

    function onError($connection, $code, $msg)
    {

    }

    function onWorkerStart($worker)
    {
        ChannelClient::connect(env('CHANNEL_IP'), env('CHANNEL_PORT'));
    }

    function onWorkerStop($worker)
    {
    }

    function onWorkerReload($worker)
    {
    }

    function onBufferFull($connection)
    {
    }

    function onBufferDrain($connection)
    {
    }
}
