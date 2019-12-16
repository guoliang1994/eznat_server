<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;

class GuardController extends Controller
{
    public function __construct(Request $request)
    {
        if ($request->path() != "api/guard/login") {
            $this->middleware("check_login");
        }
    }

    public function login(Request $request)
    {
        $account = $request->input("account");
        $password = md5($request->input("password"));
        $data = User::where(['account' => $account, 'password' => $password])->first();
        if (!empty($data)) {
            $token = md5(uniqid());
            \Cache::put($token, $data, 1440);
            $response = ['code' => 20000,  'data' => ['token' =>$token], 'msg' => '登录成功', 'user_info' => \Cache::get($token)];
            return $response;
        } else {
            return ['code' => -1, 'msg' => '账号或者密码错误'];
        }
    }
    public function getUserInfo(Request $request)
    {
        $token = $request->token;
        return ['code' => 20000, 'data' => [\Cache::get($token),'roles' =>['admin']]];
    }
    public function logout()
    {
        return ['code' => 20000, 'msg' => '登出成功'];
    }
}
