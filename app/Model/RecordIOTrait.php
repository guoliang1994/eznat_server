<?php

namespace App\Model;

trait RecordIOTrait
{
    public function getIAttribute($value)
    {
        return floor($value / 1024 / 1024);
    }
    public function getOAttribute($value)
    {
        return floor($value / 1024 / 1024);
    }
}
