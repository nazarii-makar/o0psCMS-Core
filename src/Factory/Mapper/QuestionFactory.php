<?php

namespace o0psCore\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Mapper\Question;

/**
 * Class QuestionFactory
 * @package o0psCore\Factory\Mapper
 */
class QuestionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Question
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $questionMapper = new Question();
        $questionMapper->setOptions($container->get('o0psCore_module_options'))
                       ->setTranslatorHelper($container->get('MvcTranslator'))
                       ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

        return $questionMapper;
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
