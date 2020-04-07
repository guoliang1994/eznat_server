<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use \App\Http\Requests\Client as ClientForm;
use Illuminate\Support\Facades\Lang;

class ClientController extends Controller
{
    protected $model = Client::class;

    public function retrieve(Request $request, Client $client)
    {
        $query = $client->newQuery()->joinRelationTable();
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
    public function myClient(Request $request, Client $client)
    {
        $data = $client->newQuery()->joinRelationTable()
            ->ofUser($this->user['id'])
            ->get();
        return ['code' => 20000, 'data' => $data];
    }
    public function updateOrCreate(ClientForm $request, Client $client)
    {
        $request->validated();
        $input = $request->all();
        if ($input['id'] <= 0) {
            $input['user_id'] = $this->user['id'];
        }

        $data = $client->newQuery()->joinRelationTable()
            ->where('client.id', '<>', $input['id'])
            ->ofClientName($input['name'])
            ->ofUser($this->user['id']
            )->get()->toArray();
        if (!empty($data)) {
            return ['code' => 0, 'message' => Lang::get('global.exists', ['name' => Lang::get('client')])];
        } else {
            $client->updateOrCreate($input);
            return ['code' => 20000, 'message' => Lang::get('global.success')];
        }
    }
}
