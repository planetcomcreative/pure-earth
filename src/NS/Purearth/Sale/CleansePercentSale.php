<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/05/17
 * Time: 10:51 AM
 */

namespace NS\Purearth\Sale;


use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Common\TimestampableTrait;
use \DateTime;
use NS\Purearth\Product\Juice;

class CleansePercentSale extends AbstractSale implements SaleVoterInterface
{
    public function supports($object)
    {
        return $object instanceof Juice && $object->getId();
    }

    /**
     * @param Juice $object
     */
    public function applyDiscount($object)
    {
//        $object->setSalePrice($object->getSalePrice() - ($object->getSalePrice() * ($this->discount/100))); //Use this if you want discounts to stack
        $object->setSalePrice($this->getFinal($object));
    }

    public function getFinal($object)
    {
        return $object->getPrice() - ($object->getPrice() * ($this->discount/100));
    }
}