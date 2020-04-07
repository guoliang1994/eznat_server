<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header("X-Token");
        $request->token = $token;
        $userInfo = \Cache::get($token);
        if (empty($userInfo)){
            $backData = ['code' => 0 , 'message' => '未登陆'];
            \Response::json($backData)->send();
            exit;
        } else {
            if ($userInfo['frozen'] == 1) {
                $backData = ['code' => 0 , 'message' => '用户被冻结'];
                \Response::json($backData)->send();
                 exit;
            }
        }
        return $next($request);
    }
}
