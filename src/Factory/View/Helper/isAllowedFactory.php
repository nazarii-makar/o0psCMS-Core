<?php

namespace o0psCore\Factory\View\Helper;

use o0psCore\View\Helper\IsAllowed;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class isAllowedFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $auth = $container->get('Zend\Authentication\AuthenticationService');
        $acl = $container->get('acl');

        $helper = new IsAllowed($auth, $acl);
        return $helper;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
