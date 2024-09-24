<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 19/09/16
 * Time: 3:39 PM
 */

namespace NS\PurearthBundle\Listeners;


use NS\Purearth\Order\Event\OrderEvent;
use NS\PurearthBundle\Service\Email;

class OrderListener
{
    /**
     * @var Email
     */
    private $email;

    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function onOrderPaid(OrderEvent $event)
    {
        $order = $event->getOrder();
        try
        {
            $this->email->sendNewOrderEmail($order);
        }
        catch(\Exception $e)
        {}
    }

}