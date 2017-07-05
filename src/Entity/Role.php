<?php

namespace o0psCore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false, unique=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[ña-zÑA-Z][ña-zÑA-Z0-9\_\-]+$/"}})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"text",
     *   "required":"true"
     * })
     */
    protected $name;

    /**
     * @var ArrayCollection $parents
     *
     * @ORM\ManyToMany(targetEntity="Role", cascade={"persist"})
     * @ORM\JoinTable(name="roles_parents",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     *      )
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(false)
     * @Annotation\Options({
     *   "required":"false",
     *   "empty_option": "Please, choose a role",
     *   "target_class":"o0psCore\Entity\Role",
     *   "property": "name"
     * })
     */
    protected $parents;

    public function __construct()
    {
        $this->parents = new ArrayCollection;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parents
     *
     * @param  ArrayCollection $parents
     *
     * @return Role
     */
    public function setParents($parents)
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * Get parents
     *
     * @return ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }
}
