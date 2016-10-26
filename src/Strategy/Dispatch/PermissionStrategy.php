<?php

namespace o0psCore\Strategy\Dispatch;

use o0psCore\Acl\Acl;
use Zend\EventManager\EventInterface;
use Zend\View\Model\ViewModel;

class PermissionStrategy implements DispatchInterface
{

    public function onDispatch(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $application */
        $application = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $application->getServiceManager();
        $auth = $sm->get('Zend\Authentication\AuthenticationService');
        $acl = $sm->get('acl');
        $role = Acl::DEFAULT_ROLE;
        if ($auth->hasIdentity()) {
            /** @var \o0psCore\Entity\User $user */
            $user = $auth->getIdentity();
            $role = $user->getRole()->getName();
        }
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        if (!$acl->hasResource($controller)) {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
        if (!$acl->isAllowed($role, $controller, $action)) {
            $response = $e->getResponse();
            $config = $sm->get('config');
            $redirect_route = $config['acl']['redirect_route'];
            if (!empty($redirect_route)) {
                $url = $e->getRouter()->assemble($redirect_route['params'], $redirect_route['options']);
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            } else {
                $content = [];
                $request = $sm->get('request');
                $uri = $request->getUri();
                $path = $uri->getPath();
                $reasonMessage = sprintf('You don\'t have permission to access %s on this server.', $path);
                $content['reasonMessage'] = $reasonMessage;
                $viewModel = new ViewModel($content);
                $viewModel->setTemplate('o0psCore/403');
                $e->setViewModel($viewModel);
            }
        }
    }
}