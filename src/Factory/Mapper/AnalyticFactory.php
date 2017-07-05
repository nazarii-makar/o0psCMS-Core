<?php

namespace o0psCore\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Mapper\Analytic;

/**
 * Class AnalyticFactory
 * @package o0psCore\Factory\Mapper
 */
class AnalyticFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Analytic
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $analyticMapper = new Analytic();
        $analyticMapper->setTranslatorHelper($container->get('MvcTranslator'))
                       ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

        return $analyticMapper;
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
