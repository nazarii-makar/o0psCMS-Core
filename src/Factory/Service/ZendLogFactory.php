<?php

namespace o0psCore\Factory\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Log\Formatter\Xml;
use Interop\Container\ContainerInterface;

/**
 * Class ZendLogFactory
 * @package o0psCore\Factory\Service
 */
class ZendLogFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $filename = 'log_' . date('Y_F') . '.xml';
        $log      = new Logger();
        /** @var \o0psCore\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('o0psCore_module_options');
        $writer        = new Stream($moduleOptions->getLogPath() . $filename);
        $formatter     = new Xml();
        $writer->setFormatter($formatter);
        $log->addWriter($writer);

        return $log;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param null                    $name
     * @param null                    $requestName
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestName = null)
    {
        return $this($serviceLocator, $requestName, []);
    }
}
