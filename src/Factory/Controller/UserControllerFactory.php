<?php

namespace o0psCore\Factory\Controller;

use o0psCore\Controller\UserController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserControllerFactory
 * @package o0psCore\Factory\Controller
 */
class UserControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UserController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new UserController();
        $controller->setOptions($container->get('o0psCore_module_options'))
                   ->setUserFormHelper($container->get('o0psCore_user_form'))
                   ->setTranslatorHelper($container->get('MvcTranslator'))
                   ->setViewHelperManager($container->get('ViewHelperManager'))
                   ->setUserManager($container->get('o0psCore_user_manager'))
                   ->setUserMapper($container->get('o0psCore_user_mapper'))
                   ->setStateMapper($container->get('o0psCore_state_mapper'))
                   ->setQuestionMapper($container->get('o0psCore_question_mapper'));

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
