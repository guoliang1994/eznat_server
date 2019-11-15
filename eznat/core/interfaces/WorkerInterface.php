<?php
namespace core\interfaces;

interface WorkerInterface extends ConnectionInterface
{
     function onWorkerStart($worker);
     function onWorkerStop($worker);
     function onWorkerReload($worker);
}

