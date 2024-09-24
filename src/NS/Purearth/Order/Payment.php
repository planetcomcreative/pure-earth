<?php

namespace NS\Purearth\Order;

use \DateTime;
use NS\Purearth\Common\TimestampableTrait;
use NS\Purearth\Order\Order;
use NS\Purearth\Common\TimestampableInterface;

/**
 * Class Payment
 * @package NS\Purearth\Order
 */
class Payment implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var string
     */
    protected $chargeId;

    /**
     * @var string
     */
    protected $last4;

    /**
     * @var string
     */
    protected $card;

    /**
     * @var string
     */
    protected $cardholder;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $currency;

    /**
     * Payment constructor.
     * @param \NS\Purearth\Order\Order $order
     * @param Charge $charge
     */
    public function __construct(Order $order, Charge $charge)
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->order    = $order;
        $this->setFromCharge($charge);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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

    /**
     * @return string
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * @param string $chargeId
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;
    }

    /**
     * @return int
     */
    public function getLast4()
    {
        return $this->last4;
    }

    /**
     * @param int $last4
     */
    public function setLast4($last4)
    {
        $this->last4 = $last4;
    }

    /**
     * @return string
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param string $card
     */
    public function setCard($card)
    {
        $this->card = $card;
    }

    /**
     * @return string
     */
    public function getCardholder()
    {
        return $this->cardholder;
    }

    /**
     * @param string $cardholder
     */
    public function setCardholder($cardholder)
    {
        $this->cardholder = $cardholder;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param Charge $charge
     */
    public function setFromCharge(Charge $charge)
    {
        $this->setChargeId($charge->getChargeId());
        $this->setAmount($charge->getAmount());
        $this->setLast4($charge->getLast4());
        $this->setCardHolder($charge->getCardHolder());
        $this->setCurrency($charge->getCurrency());
        $this->setCard($charge->getCard());
    }
}