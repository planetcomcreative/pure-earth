<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 05/07/16
 * Time: 2:31 PM
 */

namespace NS\Purearth\Product\Service;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;

class ProductService
{
    protected $entityMgr;

    public function __construct($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function find($pid)
    {
        $product = $this->entityMgr->getRepository('NS\Purearth\Product\AbstractProduct')->find($pid);
        if (!$product || empty($product))
        {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $products = $this->entityMgr->getRepository('NS\Purearth\Product\AbstractProduct')->findBy($criteria, $orderBy, $limit, $offset);
        if (!$products || empty($products))
        {
            throw new ProductNotFoundException();
        }

        return $products;
    }

}