<?php

namespace PartnerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partner
 *
 * @ORM\Table(name="partner")
 * @ORM\Entity(repositoryClass="PartnerBundle\Repository\PartnerRepository")
 */
class Partner
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255, unique=true)
     */
    private $place;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;

    /**
     * @var datetime
     *
     * @ORM\Column(name="enabled_date", type="datetime")
     */
    private $enabledDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;


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
     * @param string $name
     * @return Partner
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
     * Set place
     *
     * @param string $place
     * @return Partner
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Partner
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgPath
     *
     * @param string $imgPath
     * @return Partner
     */
    public function setImgPath($imgPath)
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * Get imgPath
     *
     * @return string 
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Partner
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Partner
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set enabledDate
     *
     * @param \DateTime $enabledDate
     *
     * @return Partner
     */
    public function setEnabledDate($enabledDate)
    {
        $this->enabledDate = $enabledDate;

        return $this;
    }

    /**
     * Get enabledDate
     *
     * @return \DateTime
     */
    public function getEnabledDate()
    {
        return $this->enabledDate;
    }
}
