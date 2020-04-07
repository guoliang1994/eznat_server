<?php

namespace App\Http\Controllers;

use App\Model\PortMap;
use core\Manage;
use Illuminate\Http\Request;

class ServerManage extends Controller
{
    private function getScriptFile(Request $r, PortMap $portMap){
        if (empty($r->input('script_list'))) {
            $query = $portMap->newQuery()->joinRelationTable();
            if ($this->user['id'] != 1) {
                $query->ofUser($this->user['id']);
            }
            $scriptList = $query->select('script_file')->get()->toArray();
            $scriptList = array_column($scriptList, "script_file");
        } else {
            $scriptList = explode(',', $r->input('script_list'));
        }
        return $scriptList;
    }
    public function stop(Request $r, PortMap $portMap)
    {
        $scriptList = $this->getScriptFile($r, $portMap);
        $out = Manage::do($scriptList, 'stop');
        return ['code' => 20000, 'message' => '停止成功', 'out' => $out];
    }
    public function restart(Request $r, PortMap$portMap)
    {
        $scriptList = $this->getScriptFile($r, $portMap);
        $out = Manage::do($scriptList, 'restart -d');
        return ['code' => 20000, 'message' => '启动成功', 'd' => $out];
    }
    public function status(Request $r)
    {
        $scriptList = explode(',', $r->input('script_list'));
        $out = Manage::do($scriptList, 'status');
        return ['code' => 20000, 'message' => '获取运行状态', 'out' => $out];
    }
}
