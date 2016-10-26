<?php

namespace o0psCore\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;

class AclDb extends ZendAcl {
    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'guest';

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $roles = $entityManager->getRepository('o0psCore\Entity\Role')->findAll();
        $resources = $entityManager->getRepository('o0psCore\Entity\Resource')->findAll();
        $privileges = $entityManager->getRepository('o0psCore\Entity\Privilege')->findAll();
        
        $this->_addRoles($roles)
             ->_addAclRules($resources, $privileges);
    }

    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     * @return $this
     */
    protected function _addRoles($roles)
    {
        /** @var \o0psCore\Entity\Role $role */
        foreach($roles as $role) {
            if (!$this->hasRole($role->getName())) {
                $parents = $role->getParents()->toArray();
                $parentNames = [];
                /** @var \o0psCore\Entity\Role $parent */
                foreach($parents as $parent) {
                    $parentNames[] = $parent->getName();
                }
                $this->addRole(new Role($role->getName()), $parentNames);
            }
        }

        return $this;
    }

    /**
     * Adds Resources/privileges to ACL
     *
     * @param array $resources
     * @param array $privileges
     * @return $this
     * @throws \Exception
     */
    protected function _addAclRules($resources, $privileges)
    {
        /** @var \o0psCore\Entity\Resource $resource */
        foreach ($resources as $resource) {
            if (!$this->hasResource($resource->getName())) {
                $this->addResource(new Resource($resource->getName()));
            }
        }

        /** @var \o0psCore\Entity\Privilege $privilege */
        foreach ($privileges as $privilege) {
            if($privilege->getPermissionAllow()) {
                $this->allow($privilege->getRole()->getName(), $privilege->getResource()->getName(), $privilege->getName());
            } else {
                $this->deny($privilege->getRole()->getName(), $privilege->getResource()->getName(), $privilege->getName());
            }
        }

        return $this;
    }
}
