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
            \Cache::put($token, $data->toArray(), 7 * 3600 * 24);
            User::where('id', $data->id)->update(['token' => $token]);
            if ($data->frozen == 1) {
                $response = ['code' => 0,  'data' => ['token' =>$token], 'message' => '用户被冻结'];
            } else {
                $response = ['code' => 20000,  'data' => ['token' =>$token], 'message' => '登录成功'];
            }
            return $response;
        } else {
            return ['code' => 0, 'message' => '账号或者密码错误'];
        }
    }
    public function getUserInfo(Request $request)
    {
        $token = $request->token;
        $userInfo = \Cache::get($token);
        return [
            'code' => 20000,
            'data' => [
                'name'=> $userInfo['name'],
                'avatar' => $userInfo['avatar'],
                'roles' =>['admin']
            ]
        ];
    }
    public function logout()
    {
        return ['code' => 20000, 'message' => '登出成功'];
    }
}
