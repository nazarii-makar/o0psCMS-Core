<?php

namespace o0psCore\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use o0psCore\Strategy\Dispatch\UnauthorizedStrategy;
use o0psCore\Strategy\Dispatch\PermissionStrategy;
use o0psCore\Strategy\Dispatch\AnalyticStrategy;

/**
 * Class DispatchListener
 * @package o0psCore\Listener
 */
class DispatchListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     * @param int                   $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [UnauthorizedStrategy::class, 'onDispatch'], -100);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [PermissionStrategy::class, 'onDispatch'], -80);
    }
}