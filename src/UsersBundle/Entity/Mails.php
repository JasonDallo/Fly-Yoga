<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mails
 *
 * @ORM\Table(name="mails")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\MailsRepository")
 */
class Mails
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var array
     *
     * @ORM\Column(name="users_mails", type="array")
     */
    private $usersMails;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_at", type="datetime")
     */
    private $sendAt;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string")
     */
    private $origin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available;

    public function __construct(){
        $this->sendAt = new \DateTime();
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
     * Set title
     *
     * @param string $title
     *
     * @return Mails
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Mails
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set usersMails
     *
     * @param array $usersMails
     *
     * @return Mails
     */
    public function setUsersMails($usersMails)
    {
        $this->usersMails = $usersMails;

        return $this;
    }

    /**
     * Get usersMails
     *
     * @return array
     */
    public function getUsersMails()
    {
        return $this->usersMails;
    }

    /**
     * Set sendAt
     *
     * @param \DateTime $sendAt
     *
     * @return Mails
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt
     *
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Mails
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set available
     *
     * @param boolean $available
     *
     * @return Mails
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return boolean
     */
    public function getAvailable()
    {
        return $this->available;
    }
}
