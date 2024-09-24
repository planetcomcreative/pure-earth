<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/08/16
 * Time: 1:58 PM
 */

namespace NS\Purearth\Order;

use NS\Purearth\Common\TimestampableTrait;
use NS\Purearth\Product\Course;
use NS\Purearth\User\User;

class CourseRegistration
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Course
     */
    protected $course;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var string
     */
    protected $registrantInfo;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * CourseRegistration constructor.
     * @param Course|null $course
     */
    public function __construct($course = null)
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->course = $course ? $course : null;
    }

    public function __toString()
    {
        return 'Registration for '.$this->course;
    }
    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getRegistrantInfo()
    {
        return $this->registrantInfo;
    }

    /**
     * @param string $registrantInfo
     */
    public function setRegistrantInfo($registrantInfo)
    {
        $this->registrantInfo = $registrantInfo;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}