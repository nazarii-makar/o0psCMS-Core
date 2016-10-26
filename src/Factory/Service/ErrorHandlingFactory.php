<?php

namespace o0psCore\Factory\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use o0psCore\Service\ErrorHandlingService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ErrorHandlingFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger = $container->get('ZendLog');
        $service = new ErrorHandlingService($logger);
        return $service;
    }

    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestName = null)
    {
        return $this($serviceLocator, $requestName, []);
    }
}