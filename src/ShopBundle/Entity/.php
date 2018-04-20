<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\testRepository")
 */
class test
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
     * @var array
     *
     * @ORM\Column(name="omg", type="array")
     */
    private $omg;


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
     * Set omg
     *
     * @param array $omg
     * @return test
     */
    public function setOmg($omg)
    {
        $this->omg = $omg;

        return $this;
    }

    /**
     * Get omg
     *
     * @return array 
     */
    public function getOmg()
    {
        return $this->omg;
    }
}
