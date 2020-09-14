<?php

namespace App\Model;

class Map extends Base
{
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function scopeJoinRelationTable($query){
        return $query->join("client", "client_id", "=", "client.id")
            ->join("user", "user_id", "=", "user.id")
           ->orderBy('user.id', 'asc')
            ->select(
                'user.name as user_name',
                'account',

                'client.name as client_name',
                'data_bus',
                'client.is_online as client_is_online',

                "{$this->table}.*"
            );
    }
    public function scopeOfClient($query, $clientId) {
        return $query->where('client_id', $clientId);
    }
}
