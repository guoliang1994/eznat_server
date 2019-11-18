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
Route::middleware("cross_domain")->prefix('port_map')->group(function () {
    Route::any('create', 'PortMapController@create');
    Route::any('delete', 'PortMapController@delete');
    Route::any('read', 'PortMapController@read');
    Route::any('update', 'PortMapController@update');
    Route::any('retrieve', 'PortMapController@retrieve');
});

Route::middleware("cross_domain")->prefix('client')->group(function () {
    Route::any('create', 'ClientController@create');
    Route::any('delete', 'ClientController@delete');
    Route::any('read', 'ClientController@read');
    Route::any('update', 'ClientController@update');
    Route::any('retrieve', 'ClientController@retrieve');
});

Route::middleware("cross_domain")->prefix('user')->group(function () {
    Route::any('create', 'UserController@create');
    Route::any('delete', 'UserController@delete');
    Route::any('read', 'UserController@read');
    Route::any('update', 'UserController@update');
    Route::any('retrieve', 'UserController@retrieve');
});

Route::middleware("cross_domain")->prefix('guard')->group(function () {
    Route::any('login', 'GuardController@login');
    Route::any('logout', 'GuardController@logout');
    Route::any('getUserInfo', 'GuardController@getUserInfo');
});

Route::middleware("cross_domain")->prefix('server_manage')->group(function () {
    Route::any('stop', 'ServerManage@stop');
    Route::any('start', 'ServerManage@start');
    Route::any('status', 'ServerManage@status');
    Route::any('restart', 'ServerManage@restart');
    Route::any('reload', 'ServerManage@reload');
});


