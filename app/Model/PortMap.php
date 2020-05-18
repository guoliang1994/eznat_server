<?php

namespace App\Model;

class PortMap extends Map
{
    protected $table = 'port_map';
    use RecordIOTrait;
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
