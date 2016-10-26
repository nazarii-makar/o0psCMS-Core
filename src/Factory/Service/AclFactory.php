<?php

namespace o0psCore\Factory\Service;

use Interop\Container\ContainerInterface;
use o0psCore\Acl\Acl;
use o0psCore\Acl\AclDb;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclFactory implements FactoryInterface
{
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

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
