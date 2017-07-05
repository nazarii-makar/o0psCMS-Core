<?php

namespace o0psCore\Factory\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use o0psCore\Service\ErrorHandlingService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ErrorHandlingFactory
 * @package o0psCore\Factory\Service
 */
class ErrorHandlingFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ErrorHandlingService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger  = $container->get('ZendLog');
        $service = new ErrorHandlingService($logger);

        return $service;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param null                    $name
     * @param null                    $requestName
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestName = null)
    {
        return $this($serviceLocator, $requestName, []);
    }
}