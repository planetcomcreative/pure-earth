<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 21/09/17
 * Time: 11:30 AM
 */

namespace NS\Purearth\Product;


class Sale
{
    /**
     * @var float
     */
    protected $price;

    /**
     * @var float
     */
    protected $salePrice;

    /**
     * @var string
     */
    protected $unit;

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

    /**
     * @return float
     */
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * @param float $salePrice
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

}