<?php

namespace NS\Purearth\Product;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Special
 * @package NS\Purearth\Product
 */
class Special extends AbstractProduct
{
    use TimedListing;

    /**
     * @var object
     */
    protected $sales;

    /**
     * @return object
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param array $sales
     */
    public function setSales(array $sales)
    {
        if(!empty($sales) && $sales === $this->sales)
        {
            reset($sales);
            $sales[key($sales)] = clone current($sales);
        }

        $this->sales = $sales;
    }


    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Specials';
    }
}