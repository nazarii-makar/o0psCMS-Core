<?php

namespace o0psCore\Strategy\Bootstrap;

use Zend\EventManager\EventInterface;

interface BootstrapInterface
{
    public function onBootstrap(EventInterface $e);
}