<?php

namespace o0psCore\Strategy\Dispatch;

use Zend\EventManager\EventInterface;

interface DispatchInterface
{
    public function onDispatch(EventInterface $e);
}