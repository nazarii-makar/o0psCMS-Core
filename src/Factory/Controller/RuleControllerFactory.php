<?php

namespace o0psCore\Factory\Controller;

use o0psCore\Controller\RuleController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RuleControllerFactory
 * @package o0psCore\Factory\Controller
 */
class RuleControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RuleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new RuleController();
        $controller->setOptions($container->get('o0psCore_module_options'))
                   ->setTranslatorHelper($container->get('MvcTranslator'))
                   ->setRuleMapper($container->get('o0psCore_rule_mapper'))
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
