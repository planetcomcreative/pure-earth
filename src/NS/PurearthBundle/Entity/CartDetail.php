<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 06/07/16
 * Time: 2:05 PM
 */

namespace NS\PurearthBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CartDetail
 * @package NS\PurearthBundle\Entity
 */
class CartDetail
{

    /**
     * @var ArrayCollection
     */
    protected $products;

    /**
     * @var array
     */
    protected $totals;

    /**
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getTotals()
    {
        return $this->totals;
    }

    /**
     * @param array $totals
     */
    public function setTotals($totals)
    {
        $this->totals = $totals;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->totals['total'];
    }

    /**
     * @param $cat
     * @return float
     */
    public function getTotalForCategory($cat)
    {
        return $this->totals[$cat];
    }

    /**
     * @return float
     */
    public function getGst()
    {
        return $this->totals['gst'];
    }

    /**
     * @return float
     */
    public function getPst()
    {
        return $this->totals['pst'];
    }

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        return $this->getTotal() + $this->getGst() + $this->getPst();
    }
}
