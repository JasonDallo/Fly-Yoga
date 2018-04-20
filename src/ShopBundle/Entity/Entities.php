<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities
 *
 * @ORM\Table(name="entities")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\EntitiesRepository")
 */
class Entities
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commande_at", type="datetime")
     */
    private $commandeAt;


    public function __construct(){
        $this->commandeAt = new \DateTime();
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
     * Set userId
     *
     * @param integer $userId
     * @return Entities
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
     * Set articleId
     *
     * @param integer $articleId
     * @return Entities
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set commandeAt
     *
     * @param \DateTime $commandeAt
     * @return Entities
     */
    public function setCommandeAt($commandeAt)
    {
        $this->commandeAt = $commandeAt;

        return $this;
    }

    /**
     * Get commandeAt
     *
     * @return \DateTime 
     */
    public function getCommandeAt()
    {
        return $this->commandeAt;
    }
}
