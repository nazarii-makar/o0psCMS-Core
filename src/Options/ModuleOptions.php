<?php

namespace o0psCore\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 * @package o0psCore\Options
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $loginRedirectRoute = 'o0ps-cms';

    /**
     * @var string
     */
    protected $logoutRedirectRoute = 'o0ps-cms/login';

    /**
     * @var string
     */
    protected $senderEmailAddress = 'no-reply@example.com';

    /**
     * @var string
     */
    protected $senderEmailName = 'Admin';

    /**
     * @var bool
     */
    protected $saveMail = true;

    /**
     * @var string
     */
    protected $mailPath = 'data\mail';

    /**
     * @var bool
     */
    protected $saveLog = true;

    /**
     * @var string
     */
    protected $logPath = 'data\\logs\\';

    /**
     * @var bool
     */
    protected $enableRegistration = true;

    /**
     * @var bool
     */
    protected $confirmEmailRegistration = true;

    /**
     * @var bool
     */
    protected $loginAfterRegistration = true;

    /**
     * @var bool
     */
    protected $defaultErrorTemplate = false;

    /**
     * @var string
     */
    protected $authenticationService = 'doctrine.authenticationservice.orm_default';

    /**
     * @return string
     */
    public function getLoginRedirectRoute()
    {
        return $this->loginRedirectRoute;
    }

    /**
     * @param string $loginRedirectRoute
     */
    public function setLoginRedirectRoute($loginRedirectRoute)
    {
        $this->loginRedirectRoute = $loginRedirectRoute;
    }

    /**
     * @return string
     */
    public function getLogoutRedirectRoute()
    {
        return $this->logoutRedirectRoute;
    }

    /**
     * @param string $logoutRedirectRoute
     */
    public function setLogoutRedirectRoute($logoutRedirectRoute)
    {
        $this->logoutRedirectRoute = $logoutRedirectRoute;
    }

    /**
     * @return string
     */
    public function getSenderEmailAddress()
    {
        return $this->senderEmailAddress;
    }

    /**
     * @param string $senderEmailAddress
     */
    public function setSenderEmailAddress($senderEmailAddress)
    {
        $this->senderEmailAddress = $senderEmailAddress;
    }

    /**
     * @return string
     */
    public function getSenderEmailName()
    {
        return $this->senderEmailName;
    }

    /**
     * @param string $senderEmailName
     */
    public function setSenderEmailName($senderEmailName)
    {
        $this->senderEmailName = $senderEmailName;
    }

    /**
     * @return boolean
     */
    public function isSaveMail()
    {
        return $this->saveMail;
    }

    /**
     * @param boolean $saveMail
     */
    public function setSaveMail($saveMail)
    {
        $this->saveMail = $saveMail;
    }

    /**
     * @return string
     */
    public function getMailPath()
    {
        return $this->mailPath;
    }

    /**
     * @param string $mailPath
     */
    public function setMailPath($mailPath)
    {
        $this->mailPath = $mailPath;
    }

    /**
     * @return boolean
     */
    public function isSaveLog()
    {
        return $this->saveLog;
    }

    /**
     * @param boolean $saveLog
     */
    public function setSaveLog($saveLog)
    {
        $this->saveLog = $saveLog;
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @param string $logPath
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;
    }

    /**
     * @return boolean
     */
    public function isEnableRegistration()
    {
        return $this->enableRegistration;
    }

    /**
     * @param boolean $enableRegistration
     */
    public function setEnableRegistration($enableRegistration)
    {
        $this->enableRegistration = $enableRegistration;
    }

    /**
     * @return boolean
     */
    public function isConfirmEmailRegistration()
    {
        return $this->confirmEmailRegistration;
    }

    /**
     * @param boolean $confirmEmailRegistration
     */
    public function setConfirmEmailRegistration($confirmEmailRegistration)
    {
        $this->confirmEmailRegistration = $confirmEmailRegistration;
    }

    /**
     * @return boolean
     */
    public function isLoginAfterRegistration()
    {
        return $this->loginAfterRegistration;
    }

    /**
     * @param boolean $loginAfterRegistration
     */
    public function setLoginAfterRegistration($loginAfterRegistration)
    {
        $this->loginAfterRegistration = $loginAfterRegistration;
    }

    /**
     * @return boolean
     */
    public function isDefaultErrorTemplate()
    {
        return $this->defaultErrorTemplate;
    }

    /**
     * @param boolean $defaultErrorTemplate
     */
    public function setDefaultErrorTemplate($defaultErrorTemplate)
    {
        $this->defaultErrorTemplate = $defaultErrorTemplate;
    }

    /**
     * @return string
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @param string $authenticationService
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
}