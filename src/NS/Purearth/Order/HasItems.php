<?php

namespace NS\Purearth\Order;

use Doctrine\Common\Collections\ArrayCollection;
use NS\Purearth\Product\AbstractProduct;

trait HasItems
{
    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param AbstractProduct $item
     */
    public function addItem(AbstractProduct $item)
    {
        $this->items->add($item);
    }

    /**
     * @param AbstractProduct $item
     */
    public function removeItem(AbstractProduct $item)
    {
        $this->items->remove($item);
    }
}