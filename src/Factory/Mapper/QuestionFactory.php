<?php

namespace o0psCore\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use o0psCore\Mapper\Question;

class QuestionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $questionMapper = new Question();
        $questionMapper->setOptions($container->get('o0psCore_module_options'))
            ->setTranslatorHelper($container->get('MvcTranslator'))
            ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

        return $questionMapper;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }
}
