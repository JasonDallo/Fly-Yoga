<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Repository
 *
 * @ORM\Table(name="repository")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\RepositoryRepository")
 */
class Repository
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;


    /**
     * @var boolean
     *
     * @ORM\Column(name="persist", type="boolean")
     */
    private $persistent;


    public function __construct(){
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Repository
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Repository
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Repository
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set persistent
     *
     * @param boolean $persistent
     *
     * @return Repository
     */
    public function setPersistent($persistent)
    {
        $this->persistent = $persistent;

        return $this;
    }

    /**
     * Get persistent
     *
     * @return boolean
     */
    public function getPersistent()
    {
        return $this->persistent;
    }
}
