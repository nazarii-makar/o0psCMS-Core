<?php

namespace o0psCore\Factory\Service;

use Interop\Container\ContainerInterface;
use o0psCore\Service\UserService as UserCredentialsService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

/**
 * Class UserManagerFactory
 * @package o0psCore\Factory\Service
 */
class UserManagerFactory implements FactoryInterface
{
    /**
     * @var \Zend\Mail\Transport\File
     */
    protected $mailTransport;

    /**
     * @var \o0psCore\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authenticationService;

    /**
     * @var \o0psCore\Factory\Service\MailFactory
     */
    protected $mailHelper;

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
        $this->setOptions($container->get('o0psCore_module_options'))
             ->setTranslatorHelper($container->get('MvcTranslator'))
             ->setEntityManager($container->get('Doctrine\ORM\EntityManager'))
             ->setAuthenticationService($container->get('Zend\Authentication\AuthenticationService'));

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
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function register($user)
    {
        $entityManager = $this->getEntityManager();
        if ($this->getOptions()->isConfirmEmailRegistration()) {
            $user->setState($entityManager->find('o0psCore\Entity\State', 1));
        } else {
            $user->setState($entityManager->find('o0psCore\Entity\State', 2));
        }
        $user->setRole($entityManager->find('o0psCore\Entity\Role', 2));
        $user->setLanguage($entityManager->find('o0psCore\Entity\Language', 1));
        $user->setRegistrationDate(new \DateTime());
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $user->setPassword(UserCredentialsService::encryptPassword($user->getPassword()));
        $user->setEmailConfirmed(false);

        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function create($user)
    {
        $user->setRegistrationDate(new \DateTime());
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $user->setPassword(UserCredentialsService::encryptPassword($user->getPassword()));
        $user->setEmailConfirmed(false);
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     * @param                              $password
     *
     * @return mixed
     */
    public function changePassword($user, $password)
    {
        $user->setPassword(UserCredentialsService::encryptPassword($password));
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     * @param                              $email
     *
     * @return mixed
     */
    public function changeEmail($user, $email)
    {
        $user->setEmail($email);
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User  $user
     * @param \o0psCore\Entity\State $state
     *
     * @return mixed
     */
    public function setUserState($user, $state)
    {
        $user->setState($state);
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return mixed
     */
    public function forgotPassword($user)
    {
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function confirmEmail($user)
    {
        $entityManager = $this->getEntityManager();
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $user->setState($entityManager->find('o0psCore\Entity\State', 2));
        $user->setEmailConfirmed(true);
        $this->update($user);

        return $user;
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return array
     */
    public function confirmEmailChangePassword($user)
    {
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $password = $this->generatePassword();
        $user->setPassword(UserCredentialsService::encryptPassword($password));
        $this->update($user);

        return ['user' => $user, 'password' => $password];
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     */
    public function remove($user)
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($user);
        $entityManager->flush();
    }

    /**
     * @param \o0psCore\Entity\User|Object $user
     *
     * @return mixed
     */
    public function update($user)
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    /**
     * @param $username
     * @param $password
     * @param $rememberMe
     *
     * @return bool|\Zend\Authentication\Result
     */
    public function authentication($username, $password, $rememberMe)
    {
        $authService = $this->getAuthenticationService();
        $adapter     = $authService->getAdapter();
        $adapter->setIdentity($username);
        $adapter->setCredential($password);

        $authResult = $authService->authenticate();
        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);

            if ($rememberMe) {
                $time           = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                $sessionManager = new SessionManager();
                $sessionManager->rememberMe($time);
            }

            return true;
        }

        return $authResult;
    }

    /**
     * @param int $l
     * @param int $c
     * @param int $n
     * @param int $s
     *
     * @return bool|string
     */
    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        $count = $c + $n + $s;
        $out   = '';
        if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);

            return false;
        } else {
            if ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
                trigger_error('Argument(s) out of range', E_USER_WARNING);

                return false;
            } else {
                if ($c > $l) {
                    trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);

                    return false;
                } else {
                    if ($n > $l) {
                        trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);

                        return false;
                    } else {
                        if ($s > $l) {
                            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);

                            return false;
                        } else {
                            if ($count > $l) {
                                trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);

                                return false;
                            }
                        }
                    }
                }
            }
        }

        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps  = strtoupper($chars);
        $nums  = "0123456789";
        $syms  = "!@#$%^&*()-+?";

        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        if ($count) {
            $tmp1 = str_split($out);
            $tmp2 = [];

            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }

            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }

            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            $tmp1 = array_slice($tmp1, 0, $l - $count);
            $tmp1 = array_merge($tmp1, $tmp2);
            shuffle($tmp1);
            $out = implode('', $tmp1);
        }

        return $out;
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
    public function getOptions()
    {
        return $this->options;
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
     *
     * @return $this
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;

        return $this;
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