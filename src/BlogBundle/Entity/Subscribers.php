<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscribers
 *
 * @ORM\Table(name="subscribers")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\SubscribersRepository")
 */
class Subscribers
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_at", type="datetime")
     */
    private $registeredAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_notif", type="datetime")
     */
    private $lastNotif;


    public function __construct(){
        $this->registeredAt = new \DateTime();
        $this->lastNotif = new \DateTime();
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
     * Set email
     *
     * @param string $email
     *
     * @return Subscribers
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
     * Set registeredAt
     *
     * @param \DateTime $registeredAt
     *
     * @return Subscribers
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * Get registeredAt
     *
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * Set lastNotif
     *
     * @param \DateTime $lastNotif
     *
     * @return Subscribers
     */
    public function setLastNotif($lastNotif)
    {
        $this->lastNotif = $lastNotif;

        return $this;
    }

    /**
     * Get lastNotif
     *
     * @return \DateTime
     */
    public function getLastNotif()
    {
        return $this->lastNotif;
    }
}

