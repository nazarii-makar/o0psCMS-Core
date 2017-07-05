<?php

namespace o0psCore\Mapper;

/**
 * Class Analytic
 * @package o0psCore\Mapper
 */
class Analytic
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @return bool|mixed
     */
    public function findBrowser()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.browser) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.browser')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findPlatform()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.platform) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.platform')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findLanguage()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.language) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.language')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findCountry()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.country) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.country')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findCity()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.city) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.city')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findRegion()
    {
        $entityManager = $this->getEntityManager();
        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(a.region) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.region')
                ->orderBy('data', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findPageViews()
    {
        $entityManager = $this->getEntityManager();

        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(SUBSTRING(a.date, 6, 2)) AS label',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('label')
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findPageVisits()
    {
        $entityManager = $this->getEntityManager();

        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    '(SUBSTRING(a.date, 1, 4)) AS dYear',
                    '(SUBSTRING(a.date, 6, 2)) AS dMonth',
                    '(SUBSTRING(a.date, 9, 2)) AS dDay',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('dYear', 'dMonth', 'dDay')
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
    }

    /**
     * @return array|bool
     */
    public function findGlobalStats()
    {
        $entityManager = $this->getEntityManager();

        try {
            $qb       = $entityManager->createQueryBuilder();
            $analytic = $qb
                ->select([
                    'a.loc',
                    $qb->expr()->count('a') . ' AS data',
                ])
                ->from('o0psCore\Entity\Analytic', 'a')
                ->addGroupBy('a.loc')
                ->where('a.loc != \'Unknown\'')
                ->getQuery()
                ->getArrayResult();
        } catch (\Exception $e) {
            return false;
        }

        return $analytic;
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