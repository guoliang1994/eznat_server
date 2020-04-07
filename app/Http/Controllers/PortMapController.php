<?php

namespace App\Http\Controllers;

use App\Http\Requests\Map;
use App\Model\PortMap;
use Illuminate\Http\Request;
use core\Manage;
use Illuminate\Support\Facades\Lang;

class PortMapController extends Controller
{
    protected $model = PortMap::class;

    public function retrieve(Request $request, PortMap $portMap)
    {
        $query = $portMap->newQuery()->joinRelationTable();

        $clientId = $request->input("client_id");
        if (!empty($clientId)) {
            $query->ofClient($clientId);
        }

        if ($this->user['id'] != 1) {
            $query->ofUser($this->user['id']);
        }
        $searchText = $request->input("search_account_or_username");
        if (!empty($searchText)) {
            $query->ofUserNameOrAccount($searchText);
        }
        $data = $query->paginate($this->limit);
        return ['code' => 20000, 'data' => $data];
    }
    public function updateOrCreate(Map $map, PortMap $portMap)
    {
        $map->validated();
        $input = $map->all();
        $data = $portMap
            ->where('id', '<>', $input['id'])
            ->where('remote_port', '=', $input['remote_port'])
            ->get()->toArray();
        if (!empty($data)) {
            return ['code' => 0, 'message' => Lang::get('global.exists', ['name' => $input['remote_port']. Lang::get('global.port_map')])];
        } else {
            Manage::generateScriptFile($input);
            $input['script_file'] = "{$input['remote_port']}_{$input['local_port']}.php";
            (new $this->model)->updateOrCreate($input);
            return ['code' => 20000, 'message' => Lang::get('global.success')];
        }
    }
}
