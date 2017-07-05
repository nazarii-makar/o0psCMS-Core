<?php

namespace o0psCore\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Mapper\User;

/**
 * Class UserFactory
 * @package o0psCore\Factory\Mapper
 */
class UserFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return User
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userMapper = new User();
        $userMapper->setOptions($container->get('o0psCore_module_options'))
                   ->setTranslatorHelper($container->get('MvcTranslator'))
                   ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

        return $userMapper;
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
