<?php

namespace NS\Purearth\Product\Service;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;

class SpecialService
{
    protected $entityMgr;

    public function __construct($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function getCurrent()
    {
        $specials = $this->entityMgr->getRepository('NS\Purearth\Product\Special')->findCurrent();
        if (!$specials || empty($specials))
        {
            throw new ProductNotFoundException();
        }

        return $specials;
    }
}