<?php

namespace o0psCore;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router'             => [
        'routes' => [
            'o0ps-cms' => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'rule'                       => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/rule',
                            'defaults' => [
                                'controller' => Controller\RuleController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'resources'  => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/resources',
                                    'defaults' => [
                                        'controller' => Controller\RuleController::class,
                                        'action'     => 'resources',
                                    ],
                                ],
                            ],
                            'privileges' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/privileges',
                                    'defaults' => [
                                        'controller' => Controller\RuleController::class,
                                        'action'     => 'privileges',
                                    ],
                                ],
                            ],
                            'roles'      => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/roles',
                                    'defaults' => [
                                        'controller' => Controller\RuleController::class,
                                        'action'     => 'roles',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'login'                      => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/login',
                            'defaults' => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'login',
                            ],
                        ],
                    ],
                    'signUp'                     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/sign-up',
                            'defaults' => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'signUp',
                            ],
                        ],
                    ],
                    'forgotPassword'             => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/forgot-password',
                            'defaults' => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'forgotPassword',
                            ],
                        ],
                    ],
                    'logout'                     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/logout',
                            'defaults' => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'logout',
                            ],
                        ],
                    ],
                    'confirmEmail'               => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/confirm-email/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults'    => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'confirmEmail',
                            ],
                        ],
                    ],
                    'confirmEmailChangePassword' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/confirm-email-change-password/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults'    => [
                                'controller' => Controller\AuthenticationController::class,
                                'action'     => 'confirmEmailChangePassword',
                            ],
                        ],
                    ],
                    'createUser'                 => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/create-user',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'createUser',
                            ],
                        ],
                    ],
                    'listUser'                   => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/user-list',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'editUser'                   => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/edit-user/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults'    => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'editUser',
                            ],
                        ],
                    ],
                    'deleteUser'                 => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/delete-user/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults'    => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'deleteUser',
                            ],
                        ],
                    ],
                    'setUserState'               => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/set-user-state/:id/:state',
                            'constraints' => [
                                'id'    => '[a-zA-Z0-9_-]+',
                                'state' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults'    => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'setUserState',
                            ],
                        ],
                    ],
                    'editProfile'                => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/edit-profile',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'editProfile',
                            ],
                        ],
                    ],
                    'changePassword'             => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/change-password',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'changePassword',
                            ],
                        ],
                    ],
                    'changeEmail'                => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/change-email',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'changeEmail',
                            ],
                        ],
                    ],
                    'changeSecurityQuestion'     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/change-security-question',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'changeSecurityQuestion',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'        => [
        'factories' => [
            Controller\AuthenticationController::class => Factory\Controller\AuthenticationControllerFactory::class,
            Controller\UserController::class           => Factory\Controller\UserControllerFactory::class,
            Controller\RuleController::class           => Factory\Controller\RuleControllerFactory::class,
            Controller\AdminController::class          => Factory\Controller\AdminControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'authenticationPlugin' => Factory\Controller\Plugin\AuthenticationPluginFactory::class,
            'isAllowed'            => Factory\Controller\Plugin\isAllowedFactory::class,
        ],
    ],
    'listeners'          => [
        MvcEvent::EVENT_BOOTSTRAP,
        MvcEvent::EVENT_ROUTE,
        MvcEvent::EVENT_DISPATCH,
        MvcEvent::EVENT_DISPATCH_ERROR,
        MvcEvent::EVENT_RENDER,
        MvcEvent::EVENT_RENDER_ERROR,
        MvcEvent::EVENT_FINISH,
    ],
    'service_manager'    => [
        'abstract_factories' => [
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'invokables'         => [
            'Listener\Bootstrap'     => Listener\BootstrapListener::class,
            'Listener\Route'         => Listener\RouteListener::class,
            'Listener\Dispatch'      => Listener\DispatchListener::class,
            'Listener\DispatchError' => Listener\DispatchErrorListener::class,
            'Listener\Render'        => Listener\RenderListener::class,
            'Listener\RenderError'   => Listener\RenderErrorListener::class,
            'Listener\Finish'        => Listener\FinishListener::class,
        ],
        'factories'          => [
            'Zend\Authentication\AuthenticationService' => Factory\Service\AuthenticationFactory::class,
            'o0psCore_module_options'                   => Factory\Service\ModuleOptionsFactory::class,
            'o0psCore_user_form'                        => Factory\Service\UserFormFactory::class,
            'o0psCore_mail'                             => Factory\Service\MailFactory::class,
            'o0psCore_user_manager'                     => Factory\Service\UserManagerFactory::class,
            'o0psCore_analytic_manager'                 => Factory\Service\AnalyticManagerFactory::class,
            'o0psCore_user_mapper'                      => Factory\Mapper\UserFactory::class,
            'o0psCore_analytic_mapper'                  => Factory\Mapper\AnalyticFactory::class,
            'o0psCore_rule_mapper'                      => Factory\Mapper\RuleFactory::class,
            'o0psCore_state_mapper'                     => Factory\Mapper\StateFactory::class,
            'o0psCore_question_mapper'                  => Factory\Mapper\QuestionFactory::class,
            'ErrorHandling'                             => Factory\Service\ErrorHandlingFactory::class,
            'ZendLog'                                   => Factory\Service\ZendLogFactory::class,
            'acl'                                       => Factory\Service\AclFactory::class,
        ],
        'aliases'            => [
            MvcEvent::EVENT_BOOTSTRAP      => 'Listener\Bootstrap',
            MvcEvent::EVENT_ROUTE          => 'Listener\Route',
            MvcEvent::EVENT_DISPATCH       => 'Listener\Dispatch',
            MvcEvent::EVENT_DISPATCH_ERROR => 'Listener\DispatchError',
            MvcEvent::EVENT_RENDER         => 'Listener\Render',
            MvcEvent::EVENT_RENDER_ERROR   => 'Listener\RenderError',
            MvcEvent::EVENT_FINISH         => 'Listener\Finish',
            'o0psCore\ModuleOptions'       => 'o0psCore_module_options',
            'o0psCore\UserForm'            => 'o0psCore_user_form',
            'o0psCore\Mail'                => 'o0psCore_mail',
            'o0psCore\UserManager'         => 'o0psCore_user_manager',
            'o0psCore\UserMapper'          => 'o0psCore_user_mapper',
        ],
    ],
    'view_helpers'       => [
        'factories'  => [
            'user'            => Factory\View\Helper\UserEntityFactory::class,
            'userDisplayName' => Factory\View\Helper\UserDisplayNameFactory::class,
            'isAllowed'       => Factory\View\Helper\isAllowedFactory::class,
        ],
        'invokables' => [
            'translate' => Translate::class,
        ],
    ],
    'view_manager'       => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map'        => [
            'o0psCore/500'    => __DIR__ . '/../view/error/500.phtml',
            'o0psCore/404'    => __DIR__ . '/../view/error/404.phtml',
            'o0psCore/403'    => __DIR__ . '/../view/error/403.phtml',
            'o0psCore/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'o0psCore/blank'  => __DIR__ . '/../view/layout/blank.phtml',
        ],
    ],
    'doctrine'           => [
        'configuration'  => [
            'orm_default' => [
                'generate_proxies' => true,
            ],
        ],
        'authentication' => [
            'orm_default' => [
                'object_manager'      => EntityManager::class,
                'identity_class'      => Entity\User::class,
                'identity_property'   => 'username',
                'credential_property' => 'password',
                'credential_callable' => 'o0psCore\Service\UserService::verifyHashedPassword',
            ],
        ],
        'driver'         => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '\..\src\Entity',
                ],
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
