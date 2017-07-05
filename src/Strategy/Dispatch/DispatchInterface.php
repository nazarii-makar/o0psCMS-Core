<?php

namespace o0psCore\Strategy\Dispatch;

use Zend\EventManager\EventInterface;

/**
 * Interface DispatchInterface
 * @package o0psCore\Strategy\Dispatch
 */
interface DispatchInterface
{
    /**
     * @param EventInterface $e
     *
     * @return mixed
     */
    public function onDispatch(EventInterface $e);
}