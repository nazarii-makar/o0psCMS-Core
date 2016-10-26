<?php

namespace o0psCore\Strategy\Route;

use Zend\EventManager\EventInterface;

interface RouteInterface
{
    public function onRoute(EventInterface $e);
}