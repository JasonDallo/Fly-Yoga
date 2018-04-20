<?php

namespace CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Creneaux
 *
 * @ORM\Table(name="creneaux")
 * @ORM\Entity(repositoryClass="CourseBundle\Repository\CreneauxRepository")
 */
class Creneaux
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
     * @ORM\Column(name="date_debut", type="string", length=255)
     */
    private $dateDebut;

    /**
     * @var float
     *
     * @ORM\Column(name="duree", type="float", nullable=true)
     */
    private $duree;

    /**
     * @var int
     *
     * @ORM\Column(name="prof_id", type="integer")
     */
    private $profId;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer")
     */
    private $nbrPlace;

        /**
     * @var int
     *
     * @ORM\Column(name="course_id", type="integer")
     */
    private $course_id;

    /**
     * @var int
     *
     * @ORM\Column(name="placeUs_uses", type="integer", nullable=true)
     */
    private $placeUsUses;

    /**
     * @var int
     *
     * @ORM\Column(name="tarif", type="integer", nullable=true)
     */
    private $tarif;

        /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    private $visibility;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_lastnotif", type="datetime")
     */
    private $dateLastnotif;



    public function __construct(){
        $this->dateLastnotif = new \DateTime();
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
     * @return Creneaux
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
     * Set dateDebut
     *
     * @param string $dateDebut
     *
     * @return Creneaux
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return string
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set duree
     *
     * @param float $duree
     *
     * @return Creneaux
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return float
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set profId
     *
     * @param integer $profId
     *
     * @return Creneaux
     */
    public function setProfId($profId)
    {
        $this->profId = $profId;

        return $this;
    }

    /**
     * Get profId
     *
     * @return int
     */
    public function getProfId()
    {
        return $this->profId;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Creneaux
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Creneaux
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set nbrPlace
     *
     * @param integer $nbrPlace
     *
     * @return Creneaux
     */
    public function setNbrPlace($nbrPlace)
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
    }

    /**
     * Get nbrPlace
     *
     * @return int
     */
    public function getNbrPlace()
    {
        return $this->nbrPlace;
    }

    /**
     * Set placeUsUses
     *
     * @param integer $placeUsUses
     *
     * @return Creneaux
     */
    public function setPlaceUsUses($placeUsUses)
    {
        $this->placeUsUses = $placeUsUses;

        return $this;
    }

    /**
     * Get placeUsUses
     *
     * @return int
     */
    public function getPlaceUsUses()
    {
        return $this->placeUsUses;
    }

    /**
     * Set tarif
     *
     * @param integer $tarif
     *
     * @return Creneaux
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return int
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set visibility
     *
     * @param boolean $visibility
     *
     * @return Creneaux
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return bool
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set dateLastnotif
     *
     * @param \DateTime $dateLastnotif
     *
     * @return Creneaux
     */
    public function setDateLastnotif($dateLastnotif)
    {
        $this->dateLastnotif = $dateLastnotif;

        return $this;
    }

    /**
     * Get dateLastnotif
     *
     * @return \DateTime
     */
    public function getDateLastnotif()
    {
        return $this->dateLastnotif;
    }

    /**
     * Set courseId
     *
     * @param integer $courseId
     *
     * @return Creneaux
     */
    public function setCourseId($courseId)
    {
        $this->course_id = $courseId;

        return $this;
    }

    /**
     * Get courseId
     *
     * @return integer
     */
    public function getCourseId()
    {
        return $this->course_id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Creneaux
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
