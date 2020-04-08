<?php

namespace App\Model;

class WebMap extends Map
{
    protected $table = 'web_map';
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
