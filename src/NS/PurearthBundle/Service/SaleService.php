<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/05/17
 * Time: 11:44 AM
 */

namespace NS\PurearthBundle\Service;


use Doctrine\ORM\EntityManager;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Sale\AbstractSale;

class SaleService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $sales = false;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function findSales()
    {
        return $this->entityManager->getRepository('NSPurearthBundle:Sale\AbstractSale')->findSales();
    }

    public function getDiscount(AbstractProduct $product)
    {
        $price = $product->getPrice();

        if(!$this->sales)
        {
            $this->sales = $this->findSales();
        }

        /**
         * @var AbstractSale $sale
         */
        foreach($this->sales as $sale)
        {
            if($sale->supports($product))
            {
                $price = $sale->getFinal($product);
            }
        }

        return $price;
    }

    public function applyDiscounts(AbstractProduct $product)
    {
        if(!$this->sales)
        {
            $this->sales = $this->findSales();
        }

        /**
         * @var AbstractSale $sale
         */
        foreach($this->sales as $sale)
        {
            if($sale->supports($product))
            {
                $sale->applyDiscount($product);
            }
        }
    }
}