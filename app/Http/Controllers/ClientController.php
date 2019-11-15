<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('check_login');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return array
     */
    public function retrieve(Request $request)
    {
        $userInfo = \Cache::get($request->token, 'default');
        $model =  Client::where(["user_id" => $userInfo['id']])->get();
        return ['code' => 20000, 'data' => $model];
    }
    public function update(Request $request)
    {
        $model =  Client::find($request->input('id'));
        $model->name = $request->input('name');
        $model->type = $request->input('type');
        $model->user_id = 1;
        $model->description =  $request->input('description');
        $model->save();
        return ['code' => 20000, 'msg' => '更新成功'];
    }
    public function create(Request $request)
    {
        $model = new Client();
        $model->name = $request->input('name');
        $model->type = $request->input('type');
        $model->user_id = 1;
        $model->description =  $request->input('description');
        $model->channel = md5(uniqid());
        $model->save();
        return ['code' => 20000, 'msg' => '创建成功'];
    }
    public function delete(Request $request)
    {
        Client::destroy($request->input('id'));
        return ['code' => 20000, 'msg' => '删除成功'];
    }
}
