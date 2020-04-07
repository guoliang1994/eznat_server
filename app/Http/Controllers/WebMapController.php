<?php

namespace App\Http\Controllers;

use App\Model\WebMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class WebMapController extends Controller
{
    protected $model = WebMap::class;
    public function retrieve(Request $request, WebMap $webMap)
    {
        $clientId = $request->input("client_id");
        $query = $webMap->newQuery()->joinRelationTable();
        if (!empty($clientId)) {
            $query->ofClient($clientId);
        }
        if ($this->user['id'] != 1) {
            $query->ofUser($this->user['id']);
        }
        $data = $query->paginate($this->limit);
        return ['code' => 20000, 'data' => $data];
    }
    public function updateOrCreate(\App\Http\Requests\WebMap $webMapForm, WebMap $webMap)
    {
        $webMapForm->validated();
        $input = $webMapForm->all();
        $data = $webMap
            ->where('id', '<>', $input['id'])
            ->where('domain', '=', $input['domain'])
            ->get()->toArray();
        if (!empty($data)) {
            return ['code' => 0, 'message' => Lang::get('global.exists', ['name' => $input['domain'] . Lang::get('global.web_map')])];
        } else {
            (new $this->model)->updateOrCreate($input);
            return ['code' => 20000, 'msg' => Lang::get('global.success')];
        }
    }
}
