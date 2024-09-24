<?php

namespace NS\Purearth\Order;

use NS\Purearth\Order\CartInterface;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Product\Exceptions\ProductUnavailableException;

class Cart implements CartInterface
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->items = array();
    }

    /**
     * @param AbstractProduct|integer $item
     * @param int $qty
     */
    public function addItem($item, $qty = 1)
    {
        if(is_integer($item))
        {
            $this->items[$item] = $qty;
        }
        else if($item->isAvailable())
        {
            $this->items[$item->getId()] = $qty;
        }
        else
        {
            throw new ProductUnavailableException('This product is no longer available.');
        }
    }

    /**
     * @param AbstractProduct|integer $item
     */
    public function removeItem($item)
    {
        if(is_integer($item))
        {
            unset($this->items[$item]);
        }
        else
        {
            unset($this->items[$item->getId()]);
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $count = 0;
        foreach($this->items as $item)
        {
            $count += $item;
        }

        return $count;
    }

    public function getItem($id)
    {
        return $this->items[$id];
    }

    public function clear()
    {
        $this->items = array();
    }
}