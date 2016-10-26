<?php

namespace o0psCore\Strategy\Dispatch;

use o0psCore\Collector\LayoutCollector;
use o0psCore\Collector\RouteCollector;
use o0psCore\Controller\AuthenticationController;
use Zend\EventManager\EventInterface;

class UnauthorizedStrategy implements DispatchInterface
{
    public function onDispatch(EventInterface $e)
    {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        $route = explode("/", $route);
        if (is_array($route))
            $route = array_shift($route);
        $controller = $e->getTarget();
        switch (get_class($controller)) {
            case AuthenticationController::class:
                $controller->layout(LayoutCollector::LAYOUT_BLANK);
                break;
            default:
                if ($route == RouteCollector::ROUTE_CMS) {
                    $authenticationPlugin = $controller->plugin('authenticationPlugin');
                    /**@var \o0psCore\Controller\Plugin\AuthenticationPlugin $authenticationPlugin */
                    if (!$authenticationPlugin->hasIdentity()) {
                        $controller->plugin('redirect')->toRoute(RouteCollector::ROUTE_LOGIN);
                    }
                    $controller->layout(LayoutCollector::LAYOUT_LAYOUT);
                }
                break;
        }
    }
}