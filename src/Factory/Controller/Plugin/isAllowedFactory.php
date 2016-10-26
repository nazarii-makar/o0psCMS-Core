<?php

namespace o0psCore\Factory\Controller\Plugin;

use o0psCore\Controller\Plugin\IsAllowed;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class isAllowedFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $auth = $container->get('Zend\Authentication\AuthenticationService');
        $acl = $container->get('acl');

        $plugin = new IsAllowed($auth, $acl);
        return $plugin;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
