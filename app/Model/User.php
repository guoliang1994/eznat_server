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
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }
    public function scopeNotFrozen($query) {
        return $query->where('frozen', 0);
    }
}
