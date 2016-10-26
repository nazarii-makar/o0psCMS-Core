<?php

namespace o0psCore\Controller;

use o0psCore\Collector\RouteCollector;
use o0psCore\Entity\User;
use o0psCore\Factory\Service\UserFormFactory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Session\SessionManager;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    /**
     * @var \o0psCore\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \o0psCore\Factory\Service\MailFactory
     */
    protected $mailHelper;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var \o0psCore\Factory\Service\UserFormFactory
     */
    protected $userFormHelper;

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authenticationService;

    /**
     * @var \Zend\Mail\Transport\File
     */
    protected $mailTransport;

    /**
     * @var \o0psCore\Factory\Service\UserManagerFactory
     */
    protected $userManager;

    /**
     * @var \o0psCore\Mapper\User
     */
    protected $userMapper;

    /**
     * @var $viewHelperManager
     */
    protected $viewHelperManager;

    /**
     * @var \Zend\View\Helper\HeadScript
     */
    protected $headScript;

    /**
     * @var \Zend\View\Helper\InlineScript
     */
    protected $inlineScript;

    /**
     * @var \Zend\View\Helper\HeadLink
     */
    protected $headLink;

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        if ($this->authenticationPlugin()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_LOGIN);
        $form->setAttributes([
            'action' => $this->url()->fromRoute(RouteCollector::ROUTE_LOGIN),
        ]);
        $messages = null;
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('usernameOrEmail', 'password', 'rememberme', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');
                $userMapper = $this->getUserMapper();
                $user = $userMapper->findByUsernameOrEmail($usernameOrEmail);
                if (!$user) {
                    $flashMessages = $this->getTranslatorHelper()->translate('The username or email is not valid!');
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }
                if ($user->getState()->getId() < 2) {
                    $flashMessages = $this->getTranslatorHelper()->translate('Your username is disabled. Please contact an administrator.');
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_WARNING)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }

                $authResult = $this->getUserManager()->authentication($user->getUsername(),
                    $this->params()->fromPost('password'),
                    $this->params()->fromPost('rememberme'));
                if (is_bool($authResult) && $authResult)
                    return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());

                $this->flashMessenger()
                    ->setNamespace(FlashMessenger::NAMESPACE_ERROR);
                foreach ($authResult->getMessages() as $message) {
                    $flashMessages = $this->getTranslatorHelper()->translate($message);
                    $this->flashMessenger()->addMessage($flashMessages);
                }

                return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
            }
        }
        $viewModel = new ViewModel([
            'form' => $form,
        ]);
        $viewModel->setTemplate('o0ps-core/authentication/login');
        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function signUpAction()
    {
        if ($this->authenticationPlugin()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        if (!$this->getOptions()->isEnableRegistration()) {
            $this->flashMessenger()
                ->setNamespace(FlashMessenger::NAMESPACE_INFO)
                ->addMessage($this->getTranslatorHelper()->translate('Registration is disabled'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_SIGNUP);
        $form->setAttributes([
            'action' => $this->url()->fromRoute(RouteCollector::ROUTE_SIGNUP),
        ]);
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('username', 'email', 'password', 'passwordVerify', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $user = $userManager->register($user);
                if ($this->getOptions()->isConfirmEmailRegistration()) {
                    $fullLink = $this->getBaseUrl() . $this->url()->fromRoute(RouteCollector::ROUTE_CONFIRMEMAIL, ['id' => $user->getRegistrationToken()]);
                    if (!$this->getMailHelper()->sendEmail(
                        $user->getEmail(),
                        $this->getTranslatorHelper()->translate('Please, confirm your registration!'),
                        sprintf($this->getTranslatorHelper()->translate('Please, click the link to confirm your registration => <a href="%s">Confirm</a>'), $fullLink)
                    )
                    ) {
                        $flashMessages = sprintf($this->getTranslatorHelper()->translate('Something went wrong when trying to send activation email! Please, try again later.'));
                        $this->flashMessenger()
                            ->setNamespace(FlashMessenger::NAMESPACE_WARNING)
                            ->addMessage($flashMessages);

                        return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                    };
                    $flashMessages = sprintf($this->getTranslatorHelper()->translate('An email has been sent to %s. Please, check your inbox and confirm your registration!'), $user->getEmail());
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_DEFAULT)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }
                if (!$this->getOptions()->isLoginAfterRegistration()) {
                    $flashMessages = $this->getTranslatorHelper()->translate('Thank you! Your registration has been confirmed.');
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }
                if ($user->getState()->getId() < 2) {
                    $flashMessages = $this->getTranslatorHelper()->translate('Your username is disabled. Please contact an administrator.');
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_WARNING)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }
                $authResult = $this->getUserManager()->authentication($user->getUsername(),
                    $this->params()->fromPost('password'),
                    $this->params()->fromPost('rememberme'));
                if (is_bool($authResult) && $authResult)
                    return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());

                $this->flashMessenger()
                    ->setNamespace(FlashMessenger::NAMESPACE_ERROR);
                foreach ($authResult->getMessages() as $message) {
                    $flashMessages = $this->getTranslatorHelper()->translate($message);
                    $this->flashMessenger()->addMessage($flashMessages);
                }

                return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);

            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
        ]);
        $viewModel->setTemplate('o0ps-core/authentication/singup');
        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function forgotPasswordAction()
    {
        if ($this->authenticationPlugin()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_FORGOTPASSWORD);
        $form->setAttributes([
            'action' => $this->url()->fromRoute(RouteCollector::ROUTE_FORGOTPASSWORD),
        ]);
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('email', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $email = $this->params()->fromPost('email');
                $userMapper = $this->getUserMapper();
                $user = $userMapper->findByEmail($email);
                if (!$user) {
                    $flashMessages = sprintf($this->getTranslatorHelper()->translate('The email is not valid!'));
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_FORGOTPASSWORD);
                }
                $userManager = $this->getUserManager();
                /** @var \o0psCore\Entity\User $user */
                $user = $userManager->forgotPassword($user);
                $fullLink = $this->getBaseUrl() . $this->url()->fromRoute(RouteCollector::ROUTE_CONFIRMEMAILCHANGEPASSWORD, ['id' => $user->getRegistrationToken()]);
                if (!$this->getMailHelper()->sendEmail(
                    $user->getEmail(),
                    $this->getTranslatorHelper()->translate('Please, confirm your request to change password!'),
                    sprintf($this->getTranslatorHelper()->translate('Hi, %s. Please, follow <a href="%s">this link</a> to confirm your request to change password.'), $user->getUsername(), $fullLink)
                )
                ) {
                    $flashMessages = sprintf($this->getTranslatorHelper()->translate('Something went wrong when trying to send activation email! Please, try again later.'), $user->getEmail());
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_WARNING)
                        ->addMessage($flashMessages);

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_FORGOTPASSWORD);
                }

                $flashMessages = sprintf($this->getTranslatorHelper()->translate('An email has been sent to %s. Please, check your inbox and confirm your request to change password!'), $user->getEmail());
                $this->flashMessenger()
                    ->setNamespace(FlashMessenger::NAMESPACE_DEFAULT)
                    ->addMessage($flashMessages);

                return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
            }
        }
        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('o0ps-core/authentication/forgot-password');
        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        /** @var \o0psCore\Controller\Plugin\AuthenticationPlugin $auth */
        $auth = $this->authenticationPlugin();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $sessionManager = new SessionManager();
            $sessionManager->forgetMe();
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }

    /**
     * @return \Zend\Http\Response
     */
    public function confirmEmailAction()
    {
        $token = $this->params()->fromRoute('id');
        if (!empty($token)) {
            $userMapper = $this->getUserMapper();
            $user = $userMapper->findByRegistrationToken($token);
            if ($user) {
                $userManager = $this->getUserManager();
                /** @var \o0psCore\Entity\User $user */
                $userManager->confirmEmail($user);

                $flashMessages = $this->getTranslatorHelper()->translate('Thank you! Your registration has been confirmed.');
                $this->flashMessenger()
                    ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                    ->addMessage($flashMessages);
            }
        }

        return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function confirmEmailChangePasswordAction()
    {
        $token = $this->params()->fromRoute('id');
        if (!empty($token)) {
            $userMapper = $this->getUserMapper();
            $user = $userMapper->findByRegistrationToken($token);
            if ($user) {
                $userManager = $this->getUserManager();
                $userCollection = $userManager->confirmEmailChangePassword($user);
                /** @var \o0psCore\Entity\User $user */
                $user = $userCollection['user'];
                $password = $userCollection['password'];
                $email = $user->getEmail();
                $fullLink = $this->getBaseUrl() . $this->url()->fromRoute(RouteCollector::ROUTE_LOGIN);
                if (!$this->getMailHelper()->sendEmail(
                    $user->getEmail(),
                    'Your password has been changed!',
                    sprintf($this->getTranslatorHelper()->translate('Hello again %s. Your new password is: %s. Please, follow this link %s to log in with your new password.'), $user->getUsername(), $password, $fullLink)
                )
                ) {
                    $flashMessage = sprintf($this->getTranslatorHelper()->translate('Something went wrong when trying to send password! Please, try again later.'), $email);
                    $this->flashMessenger()
                        ->setNamespace(FlashMessenger::NAMESPACE_WARNING)
                        ->addMessage($flashMessage);
                    return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
                }
                $flashMessage = sprintf($this->getTranslatorHelper()->translate('Confirmation successful! You have a new password. An email has been sent to %s with your new password.'), $email);
                $this->flashMessenger()
                    ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                    ->addMessage($flashMessage);
            }
        }

        return $this->redirect()->toRoute(RouteCollector::ROUTE_LOGIN);
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        $uri = $this->getRequest()->getUri();
        return sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return \o0psCore\Options\ModuleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $translatorHelper
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
    protected function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }

    /**
     * @param $userFormHelper
     * @return $this
     */
    public function setUserFormHelper($userFormHelper)
    {
        $this->userFormHelper = $userFormHelper;
        return $this;

    }

    /**
     * get userFormHelper
     *
     * @return  \o0psCore\Factory\Service\UserFormFactory
     */
    protected function getUserFormHelper()
    {
        return $this->userFormHelper;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @param \Zend\Authentication\AuthenticationService $authenticationService
     * @return $this
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * @return \o0psCore\Factory\Service\MailFactory
     */
    public function getMailHelper()
    {
        return $this->mailHelper;
    }

    /**
     * @param \o0psCore\Factory\Service\MailFactory $mailHelper
     * @return $this
     */
    public function setMailHelper($mailHelper)
    {
        $this->mailHelper = $mailHelper;
        return $this;
    }

    /**
     * @return \o0psCore\Factory\Service\UserManagerFactory
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param \o0psCore\Factory\Service\UserManagerFactory $userManager
     * @return $this
     */
    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
        return $this;
    }

    /**
     * @return \o0psCore\Mapper\User
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * @param \o0psCore\Mapper\User $userMapper
     * @return $this
     */
    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * @param $viewHelperManager
     * @return $this
     */
    public function setViewHelperManager($viewHelperManager)
    {
        $this->viewHelperManager = $viewHelperManager;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getViewHelperManager()
    {
        if (null === $this->viewHelperManager)
            throw new RuntimeException('No ViewHelperManager instance provided');

        return $this->viewHelperManager;
    }

    /**
     * @return \Zend\View\Helper\HeadScript
     */
    protected function getHeadScript()
    {
        if (null === $this->headScript)
            $this->headScript = $this->getViewHelperManager()->get('HeadScript');

        return $this->headScript;
    }

    /**
     * @return \Zend\View\Helper\InlineScript
     */
    protected function getInlineScript()
    {
        if (null === $this->inlineScript)
            $this->inlineScript = $this->getViewHelperManager()->get('InlineScript');

        return $this->inlineScript;
    }

    /**
     * @return \Zend\View\Helper\HeadLink
     */
    protected function getHeadLink()
    {
        if (null === $this->headLink)
            $this->headLink = $this->getViewHelperManager()->get('HeadLink');

        return $this->headLink;
    }
}
