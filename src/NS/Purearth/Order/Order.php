<?php

namespace NS\Purearth\Order;

use \DateTime;
use Doctrine\Common\Collections\Criteria;
use NS\Purearth\Common\TimestampableTrait;
use NS\Purearth\Order\Payment;
use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Product\Course;
use NS\Purearth\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class Order implements TimestampableInterface
{
    use HasItems,
        HasUser,
        TimestampableTrait;
    /**
     * @var int
     */
    protected $id;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var boolean
     */
    protected $delivery;

    /*
     * Delivery charge, gst, and pst can potentially change after the order is placed,
     * so we want to record what they actually were when the order was paid for.
     */

    /**
     * @var float
     */
    protected $deliveryCharge;

    /**
     * @var float
     */
    protected $gstRate;

    /**
     * @var float
     */
    protected $pstRate;

    /**
     * @var string
     */
    protected $deliveryAddress;

    /**
     * @var ArrayCollection
     */
    protected $orderProducts;

    /**
     * @var string
     */
    protected $comments;

    /**
     * @var ArrayCollection
     */
    protected $courseRegistrations;

    public function __construct(User $user)
    {
        $this->date = $this->createdAt = $this->updatedAt = new \DateTime();

        $this->setUser($user);
        $this->setOpen();
        $this->orderProducts = new ArrayCollection();
        $this->courseRegistrations = new ArrayCollection();
        $this->delivery = false;
        $this->pstRate = null;
        $this->gstRate = null;
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusText()
    {
        return OrderStatus::getValue($this->status);
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \NS\Purearth\Order\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param \NS\Purearth\Order\Payment $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return boolean
     */
    public function hasDelivery()
    {
        return $this->delivery;
    }

    public function isDelivery()
    {
        return $this->hasDelivery();
    }

    /**
     * @param boolean $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderProducts($showAll = false)
    {
        $prods = [];

        foreach($this->orderProducts as $prod)
        {
            if($showAll || $prod->isAvailable())
            {
                $prods[] = $prod;
            }
        }

        return $prods;
    }

    public function addOrderProduct(OrderProduct $orderProduct)
    {
        $this->orderProducts[] = $orderProduct;
    }

    /**
     * @param CartInterface $cart
     * @param array $refs
     */
    public function populateFromCart(CartInterface $cart, $refs)
    {
        foreach($cart->getItems() as $itemId => $qty)
        {
            if($refs[$itemId]->isAvailable())
            {
                $prod = new OrderProduct($refs[$itemId], $qty, $refs[$itemId]->getSalePrice());
                $prod->setOrder($this);
                $this->addOrderProduct($prod);
            }
        }
    }

    public function setOpen()
    {
        $this->status = OrderStatus::OPEN;
    }

    public function setPaid()
    {
        $this->status = OrderStatus::PAID;
    }

    public function setPaymentDeclined()
    {
        $this->status = OrderStatus::PAYMENT_DECLINED;
    }

    public function setCancelled()
    {
        $this->status = OrderStatus::CANCELLED;
    }

    public function setRefunded()
    {
        $this->status = OrderStatus::REFUNDED;
    }

    public function getSubtotal($noround = false)
    {
        $total = 0;
        foreach($this->getOrderProducts() as $op)
        {
            if($op->isAvailable())
            {
                $total += $op->getPrice() * $op->getQuantity();
            }
        }

        return $total;
    }

    public function getGst($gst_rate = 0, $noround = false)
    {
        $total = 0;
        foreach($this->getOrderProducts() as $op)
        {
            if($op->isAvailable())
            {
                $total += $op->getProduct()->hasGst() ? $op->getPrice() * ($this->gstRate ? $this->gstRate : $gst_rate) * $op->getQuantity() : 0;
            }
        }

        return $total;
    }

    public function getPst($pst_rate = 0, $noround = false)
    {
        $total = 0;
        foreach($this->getOrderProducts() as $op)
        {
            if($op->isAvailable())
            {
                $total += $op->getProduct()->hasPst() ? $op->getPrice() * ($this->pstRate ? $this->pstRate : $pst_rate) * $op->getQuantity() : 0;
            }
        }

        return $total;
    }

    public function getTotal($gst_rate = 0, $pst_rate = 0, $noround = false)
    {
        return $this->getSubtotal($noround) + $this->getGst($gst_rate, $noround) + $this->getPst($pst_rate, $noround);
    }

    public function getChargeTotal($noround = false)
    {
        $chargeTotal = $this->getTotal($this->gstRate, $this->pstRate, $noround) + ($this->hasDelivery() ? $this->getDeliveryCharge() : 0);
        return round($chargeTotal * 100)/100; //Force to two decimal places
    }

    /**
     * @param Charge $charge
     */
    public function pay(Charge $charge)
    {
        $payment = new Payment($this, $charge);

        $this->setDate(new DateTime());

        $this->setPayment($payment);
        $this->setStatus(OrderStatus::PAID);
    }

    public function __toString()
    {
        return 'Order #'.$this->id;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress()
    {
        return $this->delivery ? $this->deliveryAddress : false;
    }

    /**
     * @param string $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return float
     */
    public function getDeliveryCharge()
    {
        return $this->deliveryCharge;
    }

    /**
     * @param float $deliveryCharge
     */
    public function setDeliveryCharge($deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;
    }

    /**
     * @return float
     */
    public function getGstRate()
    {
        return $this->gstRate;
    }

    /**
     * @param float $gstRate
     */
    public function setGstRate($gstRate)
    {
        $this->gstRate = $gstRate;
    }

    /**
     * @return float
     */
    public function getPstRate()
    {
        return $this->pstRate;
    }

    /**
     * @param float $pstRate
     */
    public function setPstRate($pstRate)
    {
        $this->pstRate = $pstRate;
    }

    public function isShippable()
    {
        foreach($this->getOrderProducts() as $oprod)
        {
            if(!($oprod->getProduct() instanceof Course))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getCourseRegistrations()
    {
        return $this->courseRegistrations;
    }

    /**
     * @param ArrayCollection $courseRegistrations
     */
    public function setCourseRegistrations($courseRegistrations)
    {
        $this->courseRegistrations = $courseRegistrations;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function isChargable()
    {
        /**
         * @var AbstractProduct $prod
         */
        foreach($this->getOrderProducts() as $prod)
        {
            if($prod->isChargable())
            {
                return true;
            }
        }

        return false;
    }
}
