<?php

namespace App\Model;

class Client extends Base
{
    protected $table = 'client';
    public function webMap()
    {
        return $this->hasMany(WebMap::class);
    }
    public function portMap()
    {
        return $this->hasMany(PortMap::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeJoinRelationTable($query){
        return $query
            ->join("user", "user_id", "=", "user.id")
            ->select(
                'user.name as user_name',
                'account',

                "{$this->table}.*"
            )->orderBy('user.id', 'asc');
    }
    public function scopeOfClientName($query, $name) {
        return $query
            ->where('client.name', 'like', "%$name%");
    }
    public function updateOrCreate($input)
    {
        $id = $input['id'];
        if (!$id) {
            $input['data_bus'] = md5(uniqid());
        }
        parent::updateOrCreate($input);
    }
}
