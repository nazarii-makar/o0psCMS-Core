<?php

namespace o0psCore\Strategy\DispatchError;

use o0psCore\Collector\RouteCollector;
use Zend\Debug\Debug;
use Zend\EventManager\EventInterface;
use Zend\Mvc\Application;
use Zend\View\Model\ViewModel;

class DispatchErrorStrategy implements DispatchErrorInterface
{
    public function onDispatchError(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $application */
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $config = $sm->get('config');
        $request = $sm->get('request');
        /** @var \o0psCore\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $sm->get('o0psCore_module_options');
        $uri = $request->getUri();
        $path = $uri->getPath();
        $path = ltrim($path, '/');
        $adminPath = $config['router']['routes'][RouteCollector::ROUTE_CMS]['options']['route'] ?? null;
        $adminPath = ltrim($adminPath, '/');
        $pathArray = explode("/", $path);
        $route = array_shift($pathArray);
        if ($route == $adminPath || $moduleOptions->isDefaultErrorTemplate()) {
            $content = [];
            if ($e->isError()) {
                switch ($e->getError()) {
                    case Application::ERROR_CONTROLLER_CANNOT_DISPATCH:
                        $reasonMessage = 'The requested controller was unable to dispatch the request.';
                        break;
                    case Application::ERROR_MIDDLEWARE_CANNOT_DISPATCH:
                        $reasonMessage = 'The requested middleware was unable to dispatch the request.';
                        break;
                    case Application::ERROR_CONTROLLER_NOT_FOUND:
                        $reasonMessage = 'The requested controller could not be mapped to an existing controller class.';
                        break;
                    case Application::ERROR_CONTROLLER_INVALID:
                        $reasonMessage = 'The requested controller was not dispatchable.';
                        break;
                    case Application::ERROR_ROUTER_NO_MATCH:
                        $reasonMessage = 'The requested URL could not be matched by routing.';
                        break;
                    case Application::ERROR_EXCEPTION:
                        $reasonMessage = 'An error occurred';
                        break;
                    default:
                        $reasonMessage = 'We cannot determine at this time why a 404 was generated.';
                        break;
                }
            } else {
                $reasonMessage = 'The page you are looking for might have been removed.';
            }
            $content['reasonMessage'] = $reasonMessage;
            $viewModel = new ViewModel();
            $exception = $e->getParam('exception');
            if ($exception) {
                $content['exception'] = $exception;
                $viewModel->setTemplate('o0psCore/500');
            } else {
                $viewModel->setTemplate('o0psCore/404');
            }
            $viewModel->setVariables($content);
            $e->setViewModel($viewModel);
        }
    }
}