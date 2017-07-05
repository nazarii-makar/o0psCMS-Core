<?php

namespace o0psCore\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use o0psCore\Entity\User;

/**
 * Class UserDisplayName
 * @package o0psCore\View\Helper
 */
class UserDisplayName extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * __invoke
     *
     * @access public
     *
     * @param \o0psCore\Entity\User $user
     *
     * @throws \DomainException
     * @return String
     */
    public function __invoke(User $user = null)
    {
        if (null === $user) {
            if ($this->getAuthService()->hasIdentity()) {
                $user = $this->getAuthService()->getIdentity();
                if (!$user instanceof User) {
                    throw new \DomainException(
                        '$user is not an instance of User',
                        500
                    );
                }
            } else {
                return false;
            }
        }
        $displayName = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        if (empty(trim($displayName))) {
            $displayName = $user->getUsername();
        }
        if (empty($displayName)) {
            $displayName = $user->getEmail();
            $displayName = substr($displayName, 0, strpos($displayName, '@'));
        }

        return $displayName;
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