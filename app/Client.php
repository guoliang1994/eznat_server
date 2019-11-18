<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'sqlite';
    protected $table = "client";
    public $timestamps = false;
}
