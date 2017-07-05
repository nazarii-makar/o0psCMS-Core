<?php

namespace o0psCore\Factory\Controller;

use o0psCore\Controller\AdminController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AdminControllerFactory
 * @package o0psCore\Factory\Controller
 */
class AdminControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdminController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new AdminController();
        $controller->setOptions($container->get('o0psCore_module_options'))
                   ->setAnalyticMapper($container->get('o0psCore_analytic_mapper'))
                   ->setTranslatorHelper($container->get('MvcTranslator'))
                   ->setViewHelperManager($container->get('ViewHelperManager'));

        return $controller;
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
