<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/07/16
 * Time: 4:45 PM
 */

namespace NS\Purearth\Order;


use Doctrine\Common\Collections\ArrayCollection;
use NS\Purearth\Product\AbstractProduct;

class OrderProduct
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var AbstractProduct
     */
    protected $product;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $price;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct($productId, $qty, $price)
    {
        $this->product = $productId;
        $this->quantity = $qty;
        $this->price = $price;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param AbstractProduct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function isAvailable()
    {
        return $this->getProduct()->isAvailable();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getProduct()->getName().' - $'.$this->price;
    }

    public function isChargable()
    {
        return $this->getProduct()->isChargable();
    }

}