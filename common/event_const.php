<?php
const EVENT_OUT_CONNECT = 1;
const EVENT_OUT_MSG = 2;
const EVENT_OUT_CLOSE = 3;

const EVENT_IN_CONNECT = 4;
const EVENT_IN_MSG = 5;
const EVENT_IN_CLOSE = 6;
const EVENT_IN_CLIENT_ONLINE = 7;
// 通道传输数据
const URI = 1;
const DATA = 2;
const CHANNEL = 3;

function env($key)
{
    return isset($_ENV[$key]) ? $_ENV[$key] : "";
}