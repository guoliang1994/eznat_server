<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PortMap extends Model
{
    protected $connection = 'sqlite';
    protected $table = "port_map";
    public function retrieve()
    {
        $this->hasOne("App\\Client", "channel", "channel");
    }
}
