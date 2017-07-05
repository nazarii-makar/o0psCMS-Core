<?php

namespace o0psCore\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class IsAllowed
 * @package o0psCore\Controller\Plugin
 */
class IsAllowed extends AbstractPlugin
{

    /**
     * @var
     */
    protected $auth;
    /**
     * @var
     */
    protected $acl;

    /**
     * IsAllowed constructor.
     *
     * @param $auth
     * @param $acl
     */
    public function __construct($auth, $acl)
    {
        $this->auth = $auth;
        $this->acl  = $acl;
    }

    /**
     * @param      $resource
     * @param null $privilege
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($resource, $privilege = null)
    {
        if ($this->auth->hasIdentity()) {
            $user = $this->auth->getIdentity()->getRole()->getName();
            if (!$this->acl->hasResource($resource)) {
                throw new \Exception('Resource ' . $resource . ' not defined');
            }

            return $this->acl->isAllowed($user, $resource, $privilege);
        } else {
            return $this->acl->isAllowed(\o0psCore\Acl\Acl::DEFAULT_ROLE, $resource, $privilege);
        }
    }

}
