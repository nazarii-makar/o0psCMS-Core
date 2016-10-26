<?php

namespace o0psCore\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use o0psCore\Strategy\Dispatch\UnauthorizedStrategy;
use o0psCore\Strategy\Dispatch\PermissionStrategy;

class DispatchListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [UnauthorizedStrategy::class, 'onDispatch']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [PermissionStrategy::class, 'onDispatch'], -100);
    }
}