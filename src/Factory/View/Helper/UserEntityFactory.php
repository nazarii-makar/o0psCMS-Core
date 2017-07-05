<?php

namespace o0psCore\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\View\Helper\UserEntity;

/**
 * Class UserEntityFactory
 * @package o0psCore\Factory\View\Helper
 */
class UserEntityFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UserEntity
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userDisplayNameHelper = new UserEntity();
        $userDisplayNameHelper->setAuthService($container->get('Zend\Authentication\AuthenticationService'));

        return $userDisplayNameHelper;
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
