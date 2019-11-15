<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use core\Manage;
class ServerManage extends Controller
{
    public function __construct()
    {
        $this->middleware('check_login');
    }

    public function stop()
    {
        $out = Manage::stop();
        return ['code' => 20000, 'message' => '停止成功', 'out' => $out];
    }
    public function start()
    {
        $out = Manage::start();
        return ['code' => 20000, 'message' => '启动成功', 'out' => $out];
    }
    public function restart()
    {
        $out = Manage::restart();
        return ['code' => 20000, 'message' => '重启成功', 'd' => $out];
    }
    public function reload()
    {
        $out = Manage::reload();
        return ['code' => 20000, 'message' => '重载成功', 'out' => $out];
    }
    public function status()
    {
        $out = Manage::status();
        return ['code' => 20000, 'message' => '获取运行状态', 'out' => $out];
    }
}
