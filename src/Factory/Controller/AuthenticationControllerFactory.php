<?php

namespace o0psCore\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Controller\AuthenticationController;

class AuthenticationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new AuthenticationController();
        $controller->setOptions($container->get('o0psCore_module_options'))
            ->setUserFormHelper($container->get('o0psCore_user_form'))
            ->setTranslatorHelper($container->get('MvcTranslator'))
            ->setAuthenticationService($container->get('Zend\Authentication\AuthenticationService'))
            ->setMailHelper($container->get('o0psCore_mail'))
            ->setViewHelperManager($container->get('ViewHelperManager'))
            ->setUserManager($container->get('o0psCore_user_manager'))
            ->setUserMapper($container->get('o0psCore_user_mapper'));

        return $controller;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
