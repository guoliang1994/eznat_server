<?php

namespace App\Model;

class User extends Base
{
    protected $table = "user";
    public function client() {
        return $this->hasMany(Client::class);
    }
    public function portMap()
    {
        return $this->hasManyThrough(PortMap::class, Client::class);
    }
    public function scopeOfUser($query, $uid){
        $query->where('id', '=', $uid);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }
}
