<?php

namespace o0psCore\Factory\Controller\Plugin;

use o0psCore\Controller\Plugin\IsAllowed;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class isAllowedFactory
 * @package o0psCore\Factory\Controller\Plugin
 */
class isAllowedFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IsAllowed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $auth = $container->get('Zend\Authentication\AuthenticationService');
        $acl  = $container->get('acl');

        $plugin = new IsAllowed($auth, $acl);

        return $plugin;
    }

    /**
     * @param ServiceLocatorInterface $container
     * @param null                    $name
     * @param null                    $requestedName
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
