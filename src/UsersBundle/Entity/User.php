<?php
// src/UsersBundle/Entity/User.php

namespace UsersBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
* @ORM\Entity(repositoryClass="UsersBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    public $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    public $lastname;

    /**
     * @ORM\Column(name="registered_at", type="datetime")
     *
     */
    public $registered_at;


    public function __construct()
    {
        parent::__construct();
        $this->registered_at = new \DateTime();
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set registeredAt
     *
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registered_at = $registeredAt;

        return $this;
    }

    /**
     * Get registeredAt
     *
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registered_at;
    }
}
