<?php

namespace o0psCore\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use o0psCore\Strategy\DispatchError\Logger;
use o0psCore\Strategy\DispatchError\DispatchErrorStrategy;

/**
 * Class DispatchErrorListener
 * @package o0psCore\Listener
 */
class DispatchErrorListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     * @param int                   $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [Logger::class, 'onDispatchError']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [DispatchErrorStrategy::class, 'onDispatchError']);
    }
}