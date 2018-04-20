<?php

namespace CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourseSubs
 *
 * @ORM\Table(name="course_subs")
 * @ORM\Entity(repositoryClass="CourseBundle\Repository\CourseSubsRepository")
 */
class CourseSubs
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
     * @ORM\Column(name="calendar_dispo_id", type="integer")
     */
    private $calendarDispoId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime")
     */
    private $dateReservation;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer", nullable=true)
     */
    private $nbrPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_name", type="string", length=255, nullable=true)
     */
    private $confirmationName;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_tel", type="string", length=255, nullable=true)
     */
    private $confirmationTel;


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
     * @return CourseSubs
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
     * Set calendarDispoId
     *
     * @param integer $calendarDispoId
     * @return CourseSubs
     */
    public function setCalendarDispoId($calendarDispoId)
    {
        $this->calendarDispoId = $calendarDispoId;

        return $this;
    }

    /**
     * Get calendarDispoId
     *
     * @return integer 
     */
    public function getCalendarDispoId()
    {
        return $this->calendarDispoId;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return CourseSubs
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime 
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * Set nbrPlace
     *
     * @param integer $nbrPlace
     * @return CourseSubs
     */
    public function setNbrPlace($nbrPlace)
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
    }

    /**
     * Get nbrPlace
     *
     * @return integer 
     */
    public function getNbrPlace()
    {
        return $this->nbrPlace;
    }

    /**
     * Set confirmationName
     *
     * @param string $confirmationName
     * @return CourseSubs
     */
    public function setConfirmationName($confirmationName)
    {
        $this->confirmationName = $confirmationName;

        return $this;
    }

    /**
     * Get confirmationName
     *
     * @return string 
     */
    public function getConfirmationName()
    {
        return $this->confirmationName;
    }

    /**
     * Set confirmationTel
     *
     * @param string $confirmationTel
     * @return CourseSubs
     */
    public function setConfirmationTel($confirmationTel)
    {
        $this->confirmationTel = $confirmationTel;

        return $this;
    }

    /**
     * Get confirmationTel
     *
     * @return string 
     */
    public function getConfirmationTel()
    {
        return $this->confirmationTel;
    }
}
