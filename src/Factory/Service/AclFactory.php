<?php

namespace o0psCore\Factory\Service;

use Interop\Container\ContainerInterface;
use o0psCore\Acl\Acl;
use o0psCore\Acl\AclDb;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclFactory
 * @package o0psCore\Factory\Service
 */
class AclFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Acl|AclDb
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        if ($config['acl']['use_database_storage'] ?? false) {
            $service = new AclDb($container->get('doctrine.entitymanager.orm_default'));
        } else {
            $service = new Acl($config);
        }

        return $service;
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
