<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 15/07/16
 * Time: 11:53 AM
 */

namespace NS\Purearth\Order;


class Charge
{
    /**
     * @var string
     */
    protected $chargeId;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var integer
     */
    protected $last4;

    /**
     * @var string
     */
    protected $card;

    /**
     * @var string
     */
    protected $cardHolder;

    /**
     * @var string
     */
    protected $currency;

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
    public function getCardHolder()
    {
        return $this->cardHolder;
    }

    /**
     * @param string $cardHolder
     */
    public function setCardHolder($cardHolder)
    {
        $this->cardHolder = $cardHolder;
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
}