#!/bin/bash
echo "启动中";
php channel_server.php restart -d > /dev/null
php database_map.php restart -d > /dev/null
sleep 3
php server.php restart -d > /dev/null
php database_map.php restart -d > /dev/null
echo "启动完成";
