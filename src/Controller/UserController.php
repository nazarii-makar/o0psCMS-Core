<?php

namespace o0psCore\Controller;

use o0psCore\Entity\User;
use o0psCore\Factory\Service\UserFormFactory;
use o0psCore\Service\UserService as UserCredentialsService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;
use o0psCore\Collector\RouteCollector;

/**
 * Class UserController
 * @package o0psCore\Controller
 */
class UserController extends AbstractActionController
{
    /**
     * @var \o0psCore\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var \o0psCore\Factory\Service\UserFormFactory
     */
    protected $userFormHelper;

    /**
     * @var \o0psCore\Factory\Service\UserManagerFactory
     */
    protected $userManager;

    /**
     * @var \o0psCore\Mapper\User
     */
    protected $userMapper;

    /**
     * @var \o0psCore\Mapper\State
     */
    protected $stateMapper;

    /**
     * @var \o0psCore\Mapper\Question
     */
    protected $questionMapper;

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
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/datatables/css/dataTables.bootstrap.min.css');

        $this->getInlineScript()
            ->appendFile('/assets/lib/datatables/js/jquery.dataTables.min.js')
            ->appendFile('/assets/lib/datatables/js/dataTables.bootstrap.min.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.html5.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.flash.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.print.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.colVis.js')
            ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.bootstrap.js')
            ->appendFile('/assets/js/app-tables-datatables.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.dataTables();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $userMapper = $this->getUserMapper();
        $users      = $userMapper->findAll();

        $viewModel = new ViewModel([
            'users' => $users,
        ]);
        $viewModel->setTemplate('o0ps-core/user/index');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function createUserAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/select2/css/select2.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/select2/js/select2.min.js')
             ->appendFile('/assets/js/app-form-select2.js')
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.formSelect2();
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $user = new User;

        $form    = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_CREATEUSER);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setValidationGroup('username', 'email', 'firstName', 'lastName', 'password', 'passwordVerify', 'language', 'state', 'role', 'question', 'answer', 'csrf');
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $userManager->create($user);

                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage($this->getTranslatorHelper()->translate('User created Successfully'));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }

        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('o0ps-core/user/new-user-form');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editUserAction()
    {

        $id = (int)$this->params()->fromRoute('id', 0);

        if ($id == 0) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }

        $userMapper = $this->getUserMapper();
        $user       = $userMapper->findById($id);
        if (!$user) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }

        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/select2/css/select2.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/select2/js/select2.min.js')
             ->appendFile('/assets/js/app-form-select2.js')
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.formSelect2();
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_EDITUSER);
        $form->setAttributes([
            'action' => $this->url()->fromRoute(RouteCollector::ROUTE_EDITUSER, ['id' => $id]),
        ]);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setValidationGroup('username', 'email', 'firstName', 'lastName', 'language', 'state', 'role', 'question', 'answer', 'csrf');
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $userManager->update($user);
                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage($this->getTranslatorHelper()->translate('User Updated Successfully'));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
        ]);
        $viewModel->setTemplate('o0ps-core/user/edit-user-form');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteUserAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if ($id == 0) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }

        $userMapper = $this->getUserMapper();
        $user       = $userMapper->findById($id);
        if (!$user) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }

        $userManager = $this->getUserManager();
        $userManager->remove($user);

        $this->flashMessenger()
             ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
             ->addMessage($this->getTranslatorHelper()->translate('User Deleted Successfully'));

        return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function setUserStateAction()
    {
        $id    = (int)$this->params()->fromRoute('id', 0);
        $state = (int)$this->params()->fromRoute('state', -1);

        if ($id == 0 || $state == -1) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID or state invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }
        $userMapper = $this->getUserMapper();
        $user       = $userMapper->findById($id);
        if (!$user) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('User ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }
        $stateMapper = $this->getStateMapper();
        $stateEntity = $stateMapper->findById($state);
        if (!$stateEntity) {
            $this->flashMessenger()
                 ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                 ->addMessage($this->getTranslatorHelper()->translate('State ID invalid'));

            return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
        }
        $userManager = $this->getUserManager();
        /** @var \o0psCore\Entity\User $user */
        /** @var \o0psCore\Entity\State $stateEntity */
        $userManager->setUserState($user, $stateEntity);

        $this->flashMessenger()
             ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
             ->addMessage($this->getTranslatorHelper()->translate('User Updated Successfully'));

        return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editProfileAction()
    {
        /** @var \o0psCore\Entity\User $user */
        $user = $this->authenticationPlugin()->getIdentity();
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/select2/css/select2.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/select2/js/select2.min.js')
             ->appendFile('/assets/js/app-form-select2.js')
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.formSelect2();
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_EDITPROFILE);
        $form->get('question')->setAttributes(['type' => 'text']);
        $question = $form->get('question');
        if (!$question->getValue()) {
            $question->setValue($this->getTranslatorHelper()->translate('Select security question'));
        } else {
            $questionMapper = $this->getQuestionMapper();
            /** @var \o0psCore\Entity\Question $questionEntity */
            $questionEntity = $questionMapper->findById($question->getValue());
            if (!$questionEntity) {
                $question->setValue($this->getTranslatorHelper()->translate('Select security question'));
            } else {
                $question->setValue($questionEntity->getQuestion());
            }
        }
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('firstName', 'lastName', 'language', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $userManager->update($user);

                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage($this->getTranslatorHelper()->translate('Profile Updated Successfully'));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
        ]);
        $viewModel->setTemplate('o0ps-core/user/edit-profile');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function changeEmailAction()
    {
        /** @var \o0psCore\Entity\User $user */
        $user = $this->authenticationPlugin()->getIdentity();

        $this->getInlineScript()
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_CHANGEEMAIL);
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('newEmail', 'newEmailVerify', 'currentPassword', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                if (!UserCredentialsService::verifyHashedPassword($user, $this->params()
                                                                              ->fromPost('currentPassword'))
                ) {
                    $this->flashMessenger()
                         ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                         ->addMessage($this->getTranslatorHelper()->translate('Your current password is not correct.'));

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_CHANGEEMAIL);
                }
                $newEmail    = $this->params()->fromPost('newEmail');
                $userManager = $this->getUserManager();
                $userManager->changeEmail($user, $newEmail);

                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage(sprintf($this->getTranslatorHelper()
                                               ->translate('Thank you! Your email has been changed to %s'), $newEmail));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }
        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('o0ps-core/user/change-email');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function changePasswordAction()
    {
        /** @var \o0psCore\Entity\User $user */
        $user = $this->authenticationPlugin()->getIdentity();

        $this->getInlineScript()
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_CHANGEPASSWORD);
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('newPassword', 'newPasswordVerify', 'currentPassword', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                if (UserCredentialsService::verifyHashedPassword($user, $this->params()->fromPost('newPassword'))) {
                    $this->flashMessenger()
                         ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                         ->addMessage($this->getTranslatorHelper()
                                           ->translate('New password should not be same as old password.'));

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_CHANGEPASSWORD);
                }
                if (!UserCredentialsService::verifyHashedPassword($user, $this->params()
                                                                              ->fromPost('currentPassword'))
                ) {
                    $this->flashMessenger()
                         ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                         ->addMessage($this->getTranslatorHelper()->translate('Your current password is not correct.'));

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_CHANGEPASSWORD);
                }

                $userManager = $this->getUserManager();
                $userManager->changePassword($user, $this->params()->fromPost('newPassword'));

                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage($this->getTranslatorHelper()
                                       ->translate('Thank you! Your password has been changed successfully.'));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }

        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('o0ps-core/user/change-password');

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function changeSecurityQuestionAction()
    {
        /** @var \o0psCore\Entity\User $user */
        $user = $this->authenticationPlugin()->getIdentity();
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/select2/css/select2.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/select2/js/select2.min.js')
             ->appendFile('/assets/js/app-form-select2.js')
             ->appendFile('/assets/lib/parsley/parsley.min.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.formSelect2();
                $('form').parsley();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $form = $this->getUserFormHelper()->createUserForm($user, UserFormFactory::NAME_CHANGESECURITYQUESTION);
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('question', 'answer', 'currentPassword', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                if (!UserCredentialsService::verifyHashedPassword($user, $this->params()
                                                                              ->fromPost('currentPassword'))
                ) {
                    $this->flashMessenger()
                         ->setNamespace(FlashMessenger::NAMESPACE_ERROR)
                         ->addMessage($this->getTranslatorHelper()
                                           ->translate('Your password is wrong. Please provide the correct password.'));

                    return $this->redirect()->toRoute(RouteCollector::ROUTE_CHANGESECURITYQUESTION);
                }
                $userManager = $this->getUserManager();
                $userManager->update($user);

                $this->flashMessenger()
                     ->setNamespace(FlashMessenger::NAMESPACE_SUCCESS)
                     ->addMessage($this->getTranslatorHelper()
                                       ->translate('Thank you! Your security question has been changed'));

                return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
            }
        }
        $viewModel = new ViewModel(['form' => $form]);
        $viewModel->setTemplate('o0ps-core/user/change-security-question');

        return $viewModel;
    }

    /**
     * @param $options
     *
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
    protected function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }

    /**
     * @param $userFormHelper
     *
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
     * @return \o0psCore\Factory\Service\UserManagerFactory
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param \o0psCore\Factory\Service\UserManagerFactory $userManager
     *
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
     *
     * @return $this
     */
    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;

        return $this;
    }

    /**
     * @return \o0psCore\Mapper\State
     */
    public function getStateMapper()
    {
        return $this->stateMapper;
    }

    /**
     * @param \o0psCore\Mapper\State $stateMapper
     *
     * @return $this
     */
    public function setStateMapper($stateMapper)
    {
        $this->stateMapper = $stateMapper;

        return $this;
    }

    /**
     * @return \o0psCore\Mapper\Question
     */
    public function getQuestionMapper()
    {
        return $this->questionMapper;
    }

    /**
     * @param \o0psCore\Mapper\Question $questionMapper
     *
     * @return $this
     */
    public function setQuestionMapper($questionMapper)
    {
        $this->questionMapper = $questionMapper;

        return $this;
    }

    /**
     * @param $viewHelperManager
     *
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
        if (null === $this->viewHelperManager) {
            throw new RuntimeException('No ViewHelperManager instance provided');
        }

        return $this->viewHelperManager;
    }

    /**
     * @return \Zend\View\Helper\HeadScript
     */
    protected function getHeadScript()
    {
        if (null === $this->headScript) {
            $this->headScript = $this->getViewHelperManager()->get('HeadScript');
        }

        return $this->headScript;
    }

    /**
     * @return \Zend\View\Helper\InlineScript
     */
    protected function getInlineScript()
    {
        if (null === $this->inlineScript) {
            $this->inlineScript = $this->getViewHelperManager()->get('InlineScript');
        }

        return $this->inlineScript;
    }

    /**
     * @return \Zend\View\Helper\HeadLink
     */
    protected function getHeadLink()
    {
        if (null === $this->headLink) {
            $this->headLink = $this->getViewHelperManager()->get('HeadLink');
        }

        return $this->headLink;
    }
}