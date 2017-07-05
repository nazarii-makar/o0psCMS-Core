<?php

namespace o0psCore\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * Resources
 *
 * @ORM\Table(name="analytic")
 * @ORM\Entity
 * @Annotation\Name("analytic")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Analytic
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
     * @ORM\Column(name="browser", type="string", length=100, nullable=false)
     */
    protected $browser;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=100, nullable=false)
     */
    protected $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=100, nullable=false)
     */
    protected $language;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=100, nullable=false)
     */
    protected $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="request", type="string", length=100, nullable=false)
     */
    protected $request;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=false)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=100, nullable=false)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100, nullable=false)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="loc", type="string", length=100, nullable=false)
     */
    protected $loc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     */
    public function setLoc($loc)
    {
        $this->loc = $loc;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param array $data
     */
    public function exchange(array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, 'set' . ucfirst($key))) {
                call_user_func([$this, 'set' . ucfirst($key)], $value);
            }
        }
    }
}
