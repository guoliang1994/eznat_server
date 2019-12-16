<?php

namespace App\Http\Controllers;

use App\Model\PortMapDao;
use App\Model\PortMap;
use Illuminate\Http\Request;

class PortMapController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return array
     */
    public function retrieve(Request $request)
    {
        $userInfo = \Cache::get($request->token, 'default');
        $portMapDao = new PortMapDao();
        $where = [
            ['client.user_id', '=', $userInfo['id']]
        ];
        $data = $portMapDao->retrieve($where);
        return ['code' => 20000, 'data' => $data];
    }
    public function update(Request $request)
    {
        $portMap =  PortMap::find($request->input('id'));
        $portMap->name = $request->input('name');
        $portMap->remote_port = $request->input('remote_port');
        $portMap->local_ip = $request->input('local_ip');
        $portMap->local_port =  $request->input('local_port');
        $portMap->description =  $request->input('description');
        $portMap->save();
        return ['code' => 20000, 'msg' => '更新成功'];
    }
    public function create(Request $request)
    {
        $portMap = new PortMap();
        $userInfo = \Cache::get($request->token);
        $portMap->name = $request->input('name');
        $portMap->remote_port = $request->input('remote_port');
        $portMap->local_ip = $request->input('local_ip');
        $portMap->user_id = $userInfo['id'];
        $portMap->local_port =  $request->input('local_port');
        $portMap->description =  $request->input('description');
        $portMap->channel = $request->input('channel');
        $portMap->save();
        return ['code' => 20000, 'msg' => '创建成功'];
    }
    public function delete(Request $request)
    {
        PortMap::destroy($request->input('id'));
        return ['code' => 20000, 'msg' => '删除成功'];
    }
}
