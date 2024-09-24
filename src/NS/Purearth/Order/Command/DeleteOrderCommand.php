<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 12/07/16
 * Time: 2:54 PM
 */

namespace NS\Purearth\Order\Command;


use NS\Purearth\Order\Order;

class DeleteOrderCommand
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * DeleteOrderCommand constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}