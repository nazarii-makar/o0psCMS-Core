<?php

namespace o0psCore\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\View\Helper\UserDisplayName;

class UserDisplayNameFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userDisplayNameHelper = new UserDisplayName();
        $userDisplayNameHelper->setAuthService($container->get('Zend\Authentication\AuthenticationService'));
        return $userDisplayNameHelper;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
