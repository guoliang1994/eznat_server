<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $limit;
    protected $user;
    public function __construct(Request $request)
    {
        $this->user =  \Cache::get($request->header("X-Token"));
        $this->limit = $request->input('limit') ?: 20;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        return (new $this->model)->patchDelete($id);
    }
}
