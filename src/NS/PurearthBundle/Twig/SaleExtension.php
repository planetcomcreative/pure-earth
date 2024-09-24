<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/05/17
 * Time: 2:24 PM
 */

namespace NS\PurearthBundle\Twig;


use NS\PurearthBundle\Service\SaleService;
use \Twig_Extension;

class SaleExtension extends Twig_Extension
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('salePrice', array($this, 'salePriceFunction'))
        ];
    }

    public function salePriceFunction($product)
    {
        return $this->saleService->getDiscount($product);
    }
}