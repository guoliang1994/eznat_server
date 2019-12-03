<?php
namespace core\conf;

class Conf
{
    public  $conf = [
        'channel_port' => '9918', # 通道的端口号
        'channel_ip' => 'eznat.istiny.cc', # 通道服务器ip地址
        'channel' => '41105316ccadea84dec6acee9519d2d1', # 通道，服务端web界面获取
        'keep_alive' => 10 # 客户端重启心跳时间，单位s
    ];
}