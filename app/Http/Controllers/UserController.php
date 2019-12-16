<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function retrieve(Request $request)
    {
        $userInfo = \Cache::get($request->token);
        if ($userInfo['id'] == 1) {
            $model =  User::all();
            return ['code' => 20000, 'data' => $model];
        } else {
            $model =  User::find($userInfo['id']);
            return ['code' => 20000, 'data' => $model];
        }
    }
    public function update(Request $request)
    {
        $model =  User::find($request->input('id'));
        $model->account = $request->input('account');
        $model->password = md5($request->input('password'));
        $model->name = $request->input('name');
        $model->mobile =  $request->input('mobile');
        $model->save();
        return ['code' => 20000, 'msg' => '更新成功'];
    }
    public function create(Request $request)
    {
        $model = new User();
        $model->account = $request->input('account');
        $model->password = md5($request->input('password'));
        $model->name = $request->input('name');
        $model->mobile =  $request->input('mobile');
        $model->save();
        return ['code' => 20000, 'msg' => '创建成功'];
    }
    public function delete(Request $request)
    {
        $id = $request->input('id');
        if ($id == 1) {
            return ['code' => -1, 'msg' => '不能删除超级管理员'];
        }
        User::destroy($id);
        return ['code' => 20000, 'msg' => '删除成功'];
    }
}
