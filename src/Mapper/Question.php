<?php

namespace o0psCore\Mapper;

/**
 * Class Question
 * @package o0psCore\Mapper
 */
class Question
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

    /**
     * @param $id
     *
     * @return bool|null|object
     */
    public function findById($id)
    {
        $entityManager = $this->getEntityManager();
        try {
            $question = $entityManager->getRepository('o0psCore\Entity\Question')
                                      ->find($id);
        } catch (\Exception $e) {
            return false;
        }

        return $question;
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
    protected function getEntityManager()
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
    protected function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }
}