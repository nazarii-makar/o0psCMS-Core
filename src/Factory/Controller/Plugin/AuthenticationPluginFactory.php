<?php

namespace o0psCore\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Controller\Plugin\AuthenticationPlugin;

class AuthenticationPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationPlugin = new AuthenticationPlugin();
        $authenticationService = $container->get('Zend\Authentication\AuthenticationService');
        $authenticationPlugin->setAuthService($authenticationService)
            ->setAuthAdapter($authenticationService->getAdapter());
        return $authenticationPlugin;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
