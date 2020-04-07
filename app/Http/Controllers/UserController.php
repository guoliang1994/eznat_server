<?php

namespace App\Http\Controllers;

use App\Model\PortMap;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $model = User::class;

    public function retrieve(Request $request, User $user)
    {
        $query = $user->newQuery();
        if ($this->user['id'] != 1) {
            $query->ofUser($this->user['id']);
        }
        $data = $query->paginate($this->limit);
        return ['code' => 20000, 'data' => $data];
    }
    public function updateOrCreate(\App\Http\Requests\User $request, User $user)
    {
        if ($this->user['id'] != 1) {
            return ['code' => 0, 'message' => '没有权限创建用户'];
        }
        $request->validated();
        $input = $request->all();
        $data = $user
            ->where('id', '<>', $input['id'])
            ->where('account', '=', $input['account'])
            ->get()->toArray();
        if (!empty($data)) {
            return ['code' => 0, 'message' => '账号已经存在'];
        }
        (new $this->model)->updateOrCreate($input);
        return ['code' => 20000, 'msg' => '操作成功'];
    }
    public function frozen(Request $request, int $uid, int $frozen)
    {
        if ($uid == 1) {
            return ['code' =>0, 'message' => '不能冻结超级管理员'];
        }
        if ($uid == $this->user['id']) {
            return ['code' =>0, 'message' => '不能冻结自己'];
        }
        if ($frozen == 1) {
            $serverManage = new ServerManage($request);
            $serverManage->stop($request, new PortMap());
        }
        $this->forceLogOut($uid);
        (new $this->model)->updateOrCreate(['frozen' => $frozen, 'id' => $uid]);
        return ['code' => 20000, 'message' => '冻结成功'];
    }
    public function delete(Request $request)
    {
        DB::transaction(function () use ($request){
            $id = $request->input('id');
            if ($id == 1) {
                return ['code' =>0, 'message' => '不能删除超级管理员'];
            }
            if ($id == $this->user['id']) {
                return ['code' =>0, 'message' => '不能删除自己'];
            }
            DB::statement("
                DELETE a,b,c,d from user a  
                join  client b on a.id=b.user_id 
                join port_map c on b.id=c.client_id 
                join web_map d on b.id=d.client_id 
                where b.user_id={$id}
            ");
            $this->forceLogOut($id);
            parent::delete($request);
        });
        return ['code' => 20000, 'message' => '删除成功'];
    }
    private function forceLogOut($userId)
    {
        $mUser = new $this->model;
        $userLoginToken = $mUser->select('token')->find($userId);
        Cache::forget($userLoginToken->token);
    }
}
