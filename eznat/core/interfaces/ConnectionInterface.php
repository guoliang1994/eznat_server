<?php
namespace core\interfaces;

interface ConnectionInterface
{
     function onMessage($connection, $data);
     function onConnect($connection);
     function onClose($connection);
     function onError($connection, $code, $msg);
     function onBufferFull($connection);
     function onBufferDrain($connection);
}

