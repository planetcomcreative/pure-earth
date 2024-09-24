<?php
/**
 * Created by PhpStorm.
 * Order: mark
 * Date: 09/08/16
 * Time: 4:10 PM
 */

namespace NS\Purearth\Order\Event;


use NS\Purearth\Order\Order;
use Symfony\Component\EventDispatcher\Event;

class OrderEvent extends Event
{
    const ORDER_PAID = 'order.paid';

    /**
     * @var Order
     */
    private $order;

    /**
     * OrderEvent constructor.
     *
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