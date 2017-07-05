<?php

namespace o0psCore\Factory\Service;

use Interop\Container\ContainerInterface;
use o0psCore\Service\AnalyticService as AnalyticCredentialsService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

/**
 * Class AnalyticManagerFactory
 * @package o0psCore\Factory\Service
 */
class AnalyticManagerFactory implements FactoryInterface
{
    /**
     * @var \Zend\Mail\Transport\File
     */
    protected $mailTransport;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->setTranslatorHelper($container->get('MvcTranslator'))
             ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

        return $this;
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

    /**
     * @param \o0psCore\Entity\Analytic|Object $analytic
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function create($analytic)
    {
        $analytic->setDate(new \DateTime());
        $this->update($analytic);

        return $analytic;
    }

    /**
     * @param \o0psCore\Entity\Analytic|Object $analytic
     */
    public function remove($analytic)
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($analytic);
        $entityManager->flush();
    }

    /**
     * @param \o0psCore\Entity\Analytic|Object $analytic
     *
     * @return mixed
     */
    public function update($analytic)
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($analytic);
        $entityManager->flush();

        return $analytic;
    }

    /**
     * @param $entityManager
     *
     * @return $this
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;

    }

    /**
     * get entityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param $translatorHelper
     *
     * @return $this
     */
    public function setTranslatorHelper($translatorHelper)
    {
        $this->translatorHelper = $translatorHelper;

        return $this;

    }

    /**
     * get translatorHelper
     *
     * @return  \Zend\Mvc\I18n\Translator
     */
    public function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }
}