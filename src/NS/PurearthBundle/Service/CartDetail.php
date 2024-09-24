<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 05/07/16
 * Time: 5:44 PM
 */

namespace NS\PurearthBundle\Service;


use Doctrine\ORM\EntityManager;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Product\Course;
use NS\Purearth\Product\Juice;
use NS\Purearth\Product\Service\ProductService;

class CartDetail
{
    private $productService,
            $cartManager,
            $saleService,
            $gst_rate,
            $pst_rate,
            $products,
            $totals;

    public function __construct(ProductService $productService, CartManager $cartManager, SaleService $saleService, $gst_rate, $pst_rate)
    {
        $this->productService = $productService;
        $this->cartManager    = $cartManager;
        $this->gst_rate       = $gst_rate;
        $this->pst_rate       = $pst_rate;
        $this->saleService    = $saleService;

        $this->init();
    }

    protected function init()
    {
        $this->products       = array();
        $this->totals         = array('total'=>0, 'gst'=>0, 'pst'=>0);
    }

    protected function getItems()
    {
        return  $this->cartManager->getCart()->getItems();
    }

    protected function calculateTaxes(AbstractProduct $prod)
    {
        $items = $this->getItems();

        if($prod->hasGst())
        {
            $this->totals['gst'] += $prod->getSalePrice() * $this->gst_rate * $items[$prod->getId()];
        }

        if($prod->hasPst())
        {
            $this->totals['pst'] += $prod->getSalePrice() * $this->pst_rate * $items[$prod->getId()];
        }
    }

    protected function calculateTotals($prod)
    {
        if(!isset($this->totals[$prod->getCategory()]))
        {
            $this->totals[$prod->getCategory()] = 0;
        }

        $items = $this->getItems();
        $this->totals[$prod->getCategory()] += $prod->getSalePrice() * $items[$prod->getId()]; //items[$prodid] is the quantity
        $this->totals['total'] += $prod->getSalePrice() * $items[$prod->getId()];
    }

    protected function getProducts($ids)
    {
        return $this->productService->findBy(array('id'=>$ids));
    }

    public function getDetails()
    {
        $this->init();

        $items = $this->getItems();

        if(!empty($items))
        {
            $dbproducts = $this->getProducts(array_keys($items));

            foreach($dbproducts as $prod)
            {
                $this->saleService->applyDiscounts($prod);

                if(!isset($this->products[$prod->getCategory()]))
                {
                    $this->products[$prod->getCategory()] = array();
                }

                $this->products[$prod->getCategory()][] = $prod;

                $this->calculateTotals($prod);

                $this->calculateTaxes($prod);
            }
        }

        $detail = new \NS\PurearthBundle\Entity\CartDetail();

        $detail->setProducts($this->products);
        $detail->setTotals($this->totals);

        return $detail;
    }

}