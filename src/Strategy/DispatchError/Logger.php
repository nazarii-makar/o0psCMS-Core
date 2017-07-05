<?php

namespace o0psCore\Strategy\DispatchError;

use Zend\EventManager\EventInterface;

/**
 * Class Logger
 * @package o0psCore\Strategy\DispatchError
 */
class Logger implements DispatchErrorInterface
{
    /**
     * @param EventInterface $e
     *
     * @return void
     */
    public function onDispatchError(EventInterface $e)
    {
        $exception = $e->getParam('exception');
        if ($exception) {
            $sm = $e->getApplication()->getServiceManager();
            /** @var \o0psCore\Options\ModuleOptions $moduleOptions */
            $moduleOptions = $sm->get('o0psCore_module_options');
            if ($moduleOptions->isSaveLog()) {
                /** @var \o0psCore\Service\ErrorHandlingService $service */
                $service = $sm->get('ErrorHandling');
                $service->logException($exception);
            }
        }
    }
}