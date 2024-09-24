<?php

namespace NS\Purearth\Product;

use NS\Purearth\Common\SortableTrait;

/**
 * Class Juice
 * @package NS\Purearth\Product
 */
class Juice extends AbstractProduct
{
    use SortableTrait;

    /**
     * Number of bottles
     *
     * @var int
     */
    protected $bottles;

    /**
     * @var ProductCategory
     */
    protected $productCategory;

    public function __construct()
    {
        parent::__construct();
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function getBottles()
    {
        return $this->bottles;
    }

    /**
     * @param int $bottles
     */
    public function setBottles($bottles)
    {
        $this->bottles = $bottles;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Juice';
    }

    /**
     * @return ProductCategory
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }

    /**
     * @param ProductCategory $productCategory
     */
    public function setProductCategory($productCategory)
    {
        $this->productCategory = $productCategory;
    }
    
}