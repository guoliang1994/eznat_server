<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'sqlite';
    protected $table = "user";
    public $timestamps = false;
}
