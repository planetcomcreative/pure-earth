<?php

namespace NS\Purearth\Product;

use \DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use NS\Purearth\Order\CourseRegistration;
use NS\Purearth\Order\OrderStatus;

/**
 * Class Course
 * @package NS\Purearth\Product
 */
class Course extends AbstractProduct
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var DateTime
     */
    protected $startTime;

    /**
     * @var DateTime
     */
    protected $endTime;
    /**
     * @var int
     */
    protected $maxSeats;

    /**
     * @var DateTime
     */
    protected $registrationCutoff;

    /**
     * @var ArrayCollection
     */
    protected $courseRegistrations;

    /**
     * @var string
     */
    protected $subtitle;

    /**
     * @var ArrayCollection
     */
    protected $newRegistrations;

    public function __construct()
    {
        parent::__construct();
        $this->courseRegistrations = new ArrayCollection();
        $this->newRegistrations = new ArrayCollection();
        $this->date = new DateTime();
    }

    /**
     * @return int
     */
    public function getMaxSeats()
    {
        return $this->maxSeats;
    }

    /**
     * @return DateTime
     */
    public function getRegistrationCutoff()
    {
        return $this->registrationCutoff;
    }

    /**
     * @param int $maxSeats
     */
    public function setMaxSeats($maxSeats)
    {
        $this->maxSeats = $maxSeats;
    }

    /**
     * @param \DateTime $registrationCutoff
     */
    public function setRegistrationCutoff(\DateTime $registrationCutoff)
    {
        $this->registrationCutoff = date_time_set($registrationCutoff, 23,59,59);
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Classes';
    }

    /**
     * @return ArrayCollection
     */
    public function getCourseRegistrations()
    {
        $out = [];

        /**
         * @var CourseRegistration $cr
         */
        foreach($this->courseRegistrations as $cr)
        {
            if($cr->getOrder() && $cr->getOrder()->getStatus() == OrderStatus::PAID)
            {
                $out[] = $cr;
            }
        }

        return $out;
    }

    /**
     * @param ArrayCollection $courseRegistrations
     */
    public function setCourseRegistrations($courseRegistrations)
    {
        $this->courseRegistrations = new ArrayCollection();

        foreach($courseRegistrations as $registration) {
            $this->addCourseRegistration($registration);
        }
    }

    public function addCourseRegistration(CourseRegistration $registration)
    {
        $registration->setCourse($this);
        $this->courseRegistrations->add($registration);
    }

    /**
     * @return ArrayCollection
     */
    public function getNewRegistrations()
    {
        return $this->newRegistrations;
    }

    /**
     * @param ArrayCollection $newRegistrations
     */
    public function setNewRegistrations($newRegistrations)
    {
        $this->newRegistrations = new ArrayCollection();

        foreach($newRegistrations as $registration) {
            $this->addNewRegistration($registration);
        }
    }

    public function addNewRegistration(CourseRegistration $registration)
    {
        if(!$this->newRegistrations)
        {
            $this->newRegistrations = new ArrayCollection();
        }

        $registration->setCourse($this);
        $this->newRegistrations->add($registration);
    }

    public function getSeatsRemaining()
    {
        return $this->maxSeats - count($this->getCourseRegistrations());
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param DateTime $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    public function isAvailable()
    {
        return new DateTime() < $this->getRegistrationCutoff() && $this->getSeatsRemaining();
    }

    public function __toString()
    {
        return $this->getName().' - '.$this->getDate()->format('Y-m-d');
    }
}