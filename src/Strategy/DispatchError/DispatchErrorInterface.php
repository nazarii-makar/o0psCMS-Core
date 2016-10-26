<?php

namespace o0psCore\Strategy\DispatchError;

use Zend\EventManager\EventInterface;

interface DispatchErrorInterface
{
    public function onDispatchError(EventInterface $e);
}