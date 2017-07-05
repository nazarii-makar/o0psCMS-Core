<?php

namespace o0psCore\Controller\Plugin;

use Zend\Authentication\Adapter\AdapterInterface as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class AuthenticationPlugin
 * @package o0psCore\Controller\Plugin
 */
class AuthenticationPlugin extends AbstractPlugin
{
    /**
     * @var AuthAdapter
     */
    protected $authAdapter;
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * Proxy convenience method
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Proxy convenience method
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     *
     */
    public function clearIdentity()
    {
        $this->getAuthService()->clearIdentity();
    }

    /**
     * Get authAdapter.
     *
     * @return AuthenticationPlugin
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     * Set authAdapter.
     *
     * @param authAdapter $authAdapter
     *
     * @return $this
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;

        return $this;
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     *
     * @return $this
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;

        return $this;
    }
}