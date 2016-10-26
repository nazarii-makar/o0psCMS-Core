<?php

namespace o0psCore\Mapper;

class User
{
    /**
     * @var \o0psCore\Options\ModuleOptions
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

    public function findByUsernameOrEmail($usernameOrEmail)
    {
        $entityManager = $this->getEntityManager();
        try {
            $user = $entityManager->createQueryBuilder()
                ->select('u')
                ->from('o0psCore\Entity\User', 'u')
                ->where('u.username = :username')
                ->orWhere('u.email = :email')
                ->setParameters(['username' => $usernameOrEmail, 'email' => $usernameOrEmail])
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            return false;
        }

        return $user;
    }

    public function findById($id)
    {
        $entityManager = $this->getEntityManager();
        try {
            $user = $entityManager->getRepository('o0psCore\Entity\User')
                ->find($id);
        } catch (\Exception $e) {
            return false;
        }

        return $user;
    }

    public function findByEmail($email)
    {
        $entityManager = $this->getEntityManager();
        try {
            $user = $entityManager->getRepository('o0psCore\Entity\User')
                ->findOneBy(['email' => $email]);
        } catch (\Exception $e) {
            return false;
        }

        return $user;
    }

    public function findByRegistrationToken($token)
    {
        $entityManager = $this->getEntityManager();
        try {
            $user = $entityManager->getRepository('o0psCore\Entity\User')->findOneBy(['registrationToken' => $token]);
        } catch (\Exception $e) {
            return false;
        }

        return $user;
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