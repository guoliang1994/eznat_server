<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['check_login'])->prefix('port_map')->group(function () {
    Route::any('delete', 'PortMapController@delete');
    Route::any('read', 'PortMapController@read');
    Route::any('update_or_create', 'PortMapController@updateOrCreate');
    Route::any('retrieve', 'PortMapController@retrieve');
});

Route::middleware(['check_login'])->prefix('client')->group(function () {
    Route::any('delete', 'ClientController@delete');
    Route::any('read', 'ClientController@read');
    Route::any('update_or_create', 'ClientController@updateOrCreate');
    Route::any('retrieve', 'ClientController@retrieve');
    Route::any('my_client', 'ClientController@myClient');
});

Route::middleware(['check_login'])->prefix('user')->group(function () {
    Route::any('delete', 'UserController@delete');
    Route::any('read', 'UserController@read');
    Route::any('frozen/{id}/{frozen}', 'UserController@frozen');
    Route::any('update_or_create', 'UserController@updateOrCreate');
    Route::any('retrieve', 'UserController@retrieve');
});

Route::middleware(['check_login'])->prefix('web_map')->group(function () {
    Route::any('retrieve', 'WebMapController@retrieve');
    Route::any('update_or_create', 'WebMapController@updateOrCreate');
    Route::any('delete', 'WebMapController@delete');
});

Route::prefix('guard')->group(function () {
    Route::any('login', 'GuardController@login');
    Route::any('logout', 'GuardController@logout');
    Route::any('getUserInfo', 'GuardController@getUserInfo');
});

Route::middleware(['check_login'])->prefix('server_manage')->group(function () {
    Route::any('stop', 'ServerManage@stop');
    Route::any('start', 'ServerManage@start');
    Route::any('status', 'ServerManage@status');
    Route::any('restart', 'ServerManage@restart');
    Route::any('reload', 'ServerManage@reload');
});

Route::prefix('krpano')->group(function () {
    Route::any('register', 'KrpanoController@register');
    Route::any('make', 'KrpanoController@make');
});

