<?php

namespace o0psCore\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Doctrine ORM implementation of User entity
 *
 * @ORM\Entity(repositoryClass="o0psCore\Entity\Repository\UserRepository")
 * @ORM\Table(name="`user`",
 *   indexes={@ORM\Index(name="search_idx", columns={"username", "first_name", "last_name", "email"})}
 * )
 * @Annotation\Name("User")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, nullable=false, unique=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[ña-zÑA-Z][ña-zÑA-Z0-9\_\-]+$/"}})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"text",
     *   "required":"true"
     * })
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false, unique=true)
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"email",
     *   "required":"true"
     * })
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":20}})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"password",
     *   "required":"true"
     * })
     */
    protected $password;

    /**
     * @var \o0psCore\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="o0psCore\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "User Role",
     *   "target_class":"o0psCore\Entity\Role",
     *   "property": "name"
     * })
     */
    protected $role;

    /**
     * @var \o0psCore\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="o0psCore\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=true)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Options({
     *   "label":"Language:",
     *   "empty_option": "User Language",
     *   "target_class":"o0psCore\Entity\Language",
     *   "property": "name"
     * })
     */
    protected $language;

    /**
     * @var \o0psCore\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="o0psCore\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "User State",
     *   "target_class":"o0psCore\Entity\State",
     *   "property": "state"
     * })
     */
    protected $state;

    /**
     * @var \o0psCore\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="o0psCore\Entity\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=true)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "Security Question",
     *   "target_class":"o0psCore\Entity\Question",
     *   "property": "question"
     * })
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=100, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StringToLower", "options":{"encoding":"UTF-8"}})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":100}})
     * @Annotation\Validator({"name":"Alnum", "options":{"allowWhiteSpace":true}})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "autocomplete":"off"
     * })
     */
    protected $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Annotation\Type("Zend\Form\Element\File")
     */
    protected $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d\TH:iP"})
     */
    protected $registrationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_token", type="string", length=32, nullable=true)
     */
    protected $registrationToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_confirmed", type="boolean", nullable=false)
     */
    protected $emailConfirmed;

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
     * Set username
     *
     * @param  string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set password
     *
     * @param  string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param  Role|Object $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set language
     *
     * @param  Language|Object $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set user state
     *
     * @param  State|Object $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get user state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set question
     *
     * @param  Question $question
     * @return User
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param  string $answer
     * @return User
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set picture
     *
     * @param  string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set registrationDate
     *
     * @param  string $registrationDate
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return string
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set registrationToken
     *
     * @param  string $registrationToken
     * @return User
     */
    public function setRegistrationToken($registrationToken)
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    /**
     * Get registrationToken
     *
     * @return string
     */
    public function getRegistrationToken()
    {
        return $this->registrationToken;
    }

    /**
     * Set emailConfirmed
     *
     * @param  string $emailConfirmed
     * @return User
     */
    public function setEmailConfirmed($emailConfirmed)
    {
        $this->emailConfirmed = $emailConfirmed;

        return $this;
    }

    /**
     * Get emailConfirmed
     *
     * @return string
     */
    public function getEmailConfirmed()
    {
        return $this->emailConfirmed;
    }
}
