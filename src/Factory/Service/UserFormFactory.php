<?php

namespace o0psCore\Factory\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use Interop\Container\ContainerInterface;
use o0psCore\Options\ModuleOptions;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFormFactory implements FactoryInterface
{

    const NAME_LOGIN                    = 'login';
    const NAME_SIGNUP                   = 'signUp';
    const NAME_EDITPROFILE              = 'editProfile';
    const NAME_CHANGEPASSWORD           = 'changePassword';
    const NAME_FORGOTPASSWORD            = 'forgotPassword';
    const NAME_CHANGEEMAIL              = 'changeEmail';
    const NAME_CHANGESECURITYQUESTION   = 'changeSecurityQuestion';
    const NAME_CREATEUSER               = 'createUser';
    const NAME_EDITUSER                 = 'editUser';

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->setOptions($container->get('o0psCore_module_options'))
            ->setTranslatorHelper($container->get('MvcTranslator'))
            ->setEntityManager($container->get('Doctrine\ORM\EntityManager'));
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }

    /**
     * @param $userEntity
     * @param string $formName
     * @return \Zend\Form\Form
     */
    public function createUserForm($userEntity, $formName = self::NAME_LOGIN)
    {
        $entityManager = $this->getEntityManager();
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $this->form = $builder->createForm($userEntity);
        $this->form->setHydrator(new DoctrineHydrator($entityManager));
        $this->form->setAttribute('method', 'post');

        $this->addCommonFields();

        switch ($formName) {
            case self::NAME_LOGIN:
                $this->addLoginFields();
                $this->addLoginFilters();
                $this->form->setAttributes([
                    'name' => 'login'
                ]);
                break;
            case self::NAME_SIGNUP:
                $this->addSignUpFields();
                $this->addSignUpFilters();
                $this->form->setAttributes([
                    'name' => 'register'
                ]);
                break;

            case self::NAME_EDITPROFILE:
                $this->form->setAttributes([
                    'name' => 'edit-profile'
                ]);
                break;

            case self::NAME_CHANGEPASSWORD:
                $this->addChangePasswordFields();
                $this->addChangePasswordFilters();
                $this->form->setAttributes([
                    'name' => 'change-password'
                ]);
                break;

            case self::NAME_FORGOTPASSWORD:
                $this->addResetPasswordFields();
                $this->addResetPasswordFilters();
                $this->form->setAttributes([
                    'name' => 'reset-password'
                ]);
                break;

            case self::NAME_CHANGEEMAIL:
                $this->addChangeEmailFields();
                $this->addChangeEmailFilters();
                $this->form->setAttributes([
                    'name' => 'change-email'
                ]);
                break;

            case self::NAME_CHANGESECURITYQUESTION:
                $this->addChangeSecurityQuestionFields();
                $this->addChangeSecurityQuestionFilters();
                $this->form->setAttributes([
                    'name' => 'change-security-question'
                ]);
                break;

            case self::NAME_CREATEUSER:
                $this->addCreateUserFields();
                $this->addCreateUserFilters();
                $this->form->setAttributes([
                    'name' => 'create-user'
                ]);
                break;

            case self::NAME_EDITUSER:
                $this->form->setAttributes([
                    'name' => 'edit-user'
                ]);
                break;

            default:
                break;
        }

        $this->form->bind($userEntity);

        return $this->form;
    }

    /**
     *
     * Common Fields
     *
     */
    protected function addCommonFields()
    {
        $this->form->add([
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ]
        ]);

        $this->form->add([
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => [
                'type' => 'submit',
            ],
        ]);
    }

    /**
     *
     * Fields for User Log In
     *
     */
    protected function addLoginFields()
    {
        $this->form->add([
            'name' => 'usernameOrEmail',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => [
                'type' => 'text',
                'required' => 'required',
            ],
        ]);

        $this->form->add([
            'name' => 'rememberme',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => [
                'label' => $this->getTranslatorHelper()->translate('Remember me?'),
            ],
        ]);
    }

    /**
     *
     * Fields for User Sign Up
     *
     */
    protected function addSignUpFields()
    {
        $this->form->add([
            'name' => 'passwordVerify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'required' => true,
                'type' => 'password',
            ],
        ]);
    }

    /**
     *
     * Fields for User Change Password
     *
     */
    protected function addChangePasswordFields()
    {
        $this->form->add([
            'name' => 'newPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'type' => 'password',
                'required' => 'true',
            ],
        ]);

        $this->form->add([
            'name' => 'newPasswordVerify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'type' => 'password',
                'required' => 'true',
            ],
        ]);

        $this->form->add([
            'name' => 'currentPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'type' => 'password',
                'required' => 'true',
            ],
        ]);
    }

    /**
     *
     * Fields for User Password Reset
     *
     */
    protected function addResetPasswordFields()
    {
    }

    /**
     *
     * Fields for User Change Email
     *
     */
    protected function addChangeEmailFields()
    {
        $this->form->add([
            'name' => 'newEmail',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => [
                'type' => 'email',
                'required' => 'true',
            ],
        ]);

        $this->form->add([
            'name' => 'newEmailVerify',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => [
                'type' => 'email',
                'required' => 'true',
            ],
        ]);

        $this->form->add([
            'name' => 'currentPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'type' => 'password',
                'required' => 'true',
            ],
        ]);
    }

    /**
     *
     * Fields for User Change Security Question
     *
     */
    protected function addChangeSecurityQuestionFields()
    {
        $this->form->add([
            'name' => 'currentPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'type' => 'password',
                'required' => 'true',
            ],
        ]);
    }

    /**
     *
     * Input fields for User Create
     *
     */
    protected function addCreateUserFields()
    {
        $this->form->add([
            'name' => 'passwordVerify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'required' => true,
                'type' => 'password',
            ],
        ]);
    }

    /**
     *
     * Input filters for User Log In
     *
     */
    protected function addLoginFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'usernameOrEmail',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                    ],
                ],
            ]
        ]));

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'rememberme',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => ['0', '1'],
                    ],
                ],
            ]
        ]));
    }

    /**
     *
     * Input filters for User SignUp
     *
     */
    protected function addSignUpFilters()
    {
        $entityManager = $this->getEntityManager();
        $this->form->getInputFilter()->get('username')->getValidatorChain()->attach(
            new NoObjectExistsValidator([
                'object_repository' => $entityManager->getRepository('o0psCore\Entity\User'),
                'fields' => ['username'],
                'messages' => [
                    'objectFound' => $this->getTranslatorHelper()->translate('This username is already taken'),
                ],
            ])
        );

        $this->form->getInputFilter()->get('email')->getValidatorChain()->attach(
            new NoObjectExistsValidator([
                'object_repository' => $entityManager->getRepository('o0psCore\Entity\User'),
                'fields' => ['email'],
                'messages' => [
                    'objectFound' => $this->getTranslatorHelper()->translate('An user with this email already exists'),
                ],
            ])
        );

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'passwordVerify',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password'
                    ],
                ],
            ],
        ]));
    }

    /**
     *
     * Input filters for User Change password
     *
     */
    protected function addChangePasswordFilters()
    {

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'newPassword',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
            ]
        ]));

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'newPasswordVerify',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'newPassword',
                    ],
                ],
            ]
        ]));

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'currentPassword',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
            ]
        ]));
    }

    /**
     *
     * Input filters for User Reset Password
     *
     */
    protected function addResetPasswordFilters()
    {
    }

    /**
     *
     * Input filters for User Change email
     *
     */
    protected function addChangeEmailFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'newEmail',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress'
                ],
                [
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getEntityManager()->getRepository('o0psCore\Entity\User'),
                        'fields' => ['email'],
                        'messages' => [
                            'objectFound' => $this->getTranslatorHelper()->translate('An user with this email already exists'),
                        ],
                    ],
                ],
            ],
        ]));

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'newEmailVerify',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress'
                ],
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'newEmail',
                    ],
                ],
            ],
        ]));

        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'currentPassword',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
            ]
        ]));
    }

    /**
     *
     * Input filters for User Change Security Question
     *
     */
    protected function addChangeSecurityQuestionFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'currentPassword',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
            ]
        ]));
    }

    /**
     *
     * Input filters for User Create
     *
     */
    protected function addCreateUserFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()->getFactory()->createInput([
            'name' => 'passwordVerify',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ],
                ],
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ]
        ]));
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
     * @return ModuleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $entityManager
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
    protected function getEntityManager()
    {
        return $this->entityManager;
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
}
