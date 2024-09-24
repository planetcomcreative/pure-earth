<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 14/07/16
 * Time: 1:45 PM
 */

namespace NS\Purearth\Order\Command;


use NS\Purearth\Order\Order;
use NS\StripeBundle\Entity\StripeResponse;

/**
 * Class PlaceOrderCommand
 * @package NS\Purearth\Order\Command
 */
class PlaceOrderCommand
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * PlaceOrderCommand constructor.
     * @param Order $order
     * @param StripeResponse $response
     * @param string $currency
     * @param string $message
     * @param $email
     * @param array $metadata
     */
    public function __construct(Order $order, $currency, array $metadata = array())
    {
        $this->order = $order;
        $this->currency = $currency;
        $this->metadata = $metadata;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}