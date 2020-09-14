<?php

namespace App\Model;

class WebMap extends Map
{
    protected $table = 'web_map';
    use RecordIOTrait;
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
