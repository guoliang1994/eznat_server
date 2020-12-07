<?php
namespace core\classes;

use Workerman\Worker;

class WorkerWithCallback extends Worker
{
    public function __construct($socket_name = '', $context_option = array())
    {
        foreach (['onWorkerStart', 'onConnect', 'onMessage', 'onClose', 'onError', 'onBufferFull', 'onBufferDrain', 'onWorkerStop', 'onWorkerReload'] as $event) {
            if (method_exists($this, $event)) {
                $this->$event = [$this, $event];
            }
        }
        parent::__construct($socket_name, $context_option);
    }
}