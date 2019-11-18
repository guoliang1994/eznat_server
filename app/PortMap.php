<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortMap extends Model
{
    protected $connection = 'sqlite';
    protected $table = "port_map";
}
