<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 09/08/16
 * Time: 4:21 PM
 */

namespace NS\Purearth\Order\Command;


use NS\Purearth\Order\Course;
use NS\Purearth\Order\Order;
use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\User\User;

class CourseRegistrationCommand
{
    /**
     * @var array
     */
    protected $regData;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var User
     */
    protected $user;

    public function __construct(array $regData, Order $order, User $user)
    {
        $this->regData = $regData;
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * @return Course
     */
    public function getRegData()
    {
        return $this->regData;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}