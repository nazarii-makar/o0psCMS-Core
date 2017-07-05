<?php

namespace o0psCore\Strategy\Bootstrap;

use Zend\EventManager\EventInterface;

/**
 * Interface BootstrapInterface
 * @package o0psCore\Strategy\Bootstrap
 */
interface BootstrapInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onBootstrap(EventInterface $e);
}