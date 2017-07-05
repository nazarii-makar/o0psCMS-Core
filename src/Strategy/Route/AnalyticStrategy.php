<?php

namespace o0psCore\Strategy\Route;

use Zend\EventManager\EventInterface;
use o0psCore\Service\ClientService;
use o0psCore\Entity\Analytic;

/**
 * Class AnalyticStrategy
 * @package o0psCore\Strategy\Dispatch
 */
class AnalyticStrategy implements RouteInterface
{
    /**
     * @param EventInterface $e
     */
    public function onRoute(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $application */
        $application = $e->getApplication();
        $sm          = $application->getServiceManager();
        /** @var \o0psCore\Factory\Service\AnalyticManagerFactory $analyticManager */
        $analyticManager = $sm->get('o0psCore_analytic_manager');
        $info            = ClientService::info();
        $client          = new Analytic();

        $client->exchange($info);
        $analyticManager->create($client);
    }
}