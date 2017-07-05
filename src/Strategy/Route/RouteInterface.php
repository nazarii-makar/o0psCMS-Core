<?php

namespace o0psCore\Strategy\Route;

use Zend\EventManager\EventInterface;

/**
 * Interface RouteInterface
 * @package o0psCore\Strategy\Route
 */
interface RouteInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onRoute(EventInterface $e);
}