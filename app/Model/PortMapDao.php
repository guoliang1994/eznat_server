<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
class PortMapDao
{
    public function retrieve($where)
    {
        return DB::connection("sqlite")
            ->table("port_map")
            ->join("client","port_map.channel", "=", "client.channel")
            ->where($where)
            ->orderBy("port_map.channel")
            ->select("port_map.*", "client.name as client")
            ->get();
    }
}
