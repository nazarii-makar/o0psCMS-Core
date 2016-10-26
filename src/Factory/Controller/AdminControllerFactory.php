<?php

namespace o0psCore\Factory\Controller;

use o0psCore\Controller\AdminController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new AdminController();
        $controller->setOptions($container->get('o0psCore_module_options'))
            ->setTranslatorHelper($container->get('MvcTranslator'))
            ->setViewHelperManager($container->get('ViewHelperManager'));

        return $controller;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
