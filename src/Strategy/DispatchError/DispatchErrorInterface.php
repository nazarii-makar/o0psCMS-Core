<?php

namespace o0psCore\Strategy\DispatchError;

use Zend\EventManager\EventInterface;

/**
 * Interface DispatchErrorInterface
 * @package o0psCore\Strategy\DispatchError
 */
interface DispatchErrorInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onDispatchError(EventInterface $e);
}