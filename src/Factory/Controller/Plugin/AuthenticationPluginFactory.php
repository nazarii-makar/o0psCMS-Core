<?php

namespace o0psCore\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Controller\Plugin\AuthenticationPlugin;

/**
 * Class AuthenticationPluginFactory
 * @package o0psCore\Factory\Controller\Plugin
 */
class AuthenticationPluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AuthenticationPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationPlugin  = new AuthenticationPlugin();
        $authenticationService = $container->get('Zend\Authentication\AuthenticationService');
        $authenticationPlugin->setAuthService($authenticationService)
                             ->setAuthAdapter($authenticationService->getAdapter());

        return $authenticationPlugin;
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
