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
            $backData = ['code' => 20000 , 'message' => '未登陆'];
            \Response::json($backData)->send();
            exit;
        }
        return $next($request);
    }
}
