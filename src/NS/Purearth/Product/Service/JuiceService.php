<?php

namespace NS\Purearth\Product\Service;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;

class JuiceService
{
    protected $entityMgr;

    public function __construct($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function findAll()
    {
        $juices = $this->entityMgr->getRepository('NS\Purearth\Product\Juice')->findAll();
        if (!$juices || empty($juices))
        {
            throw new ProductNotFoundException();
        }

        return $juices;
    }

    public function find($id)
    {
        $juice = $this->entityMgr->getRepository('NS\Purearth\Product\Juice')->find($id);
        if (!$juice || empty($juice))
        {
            throw new ProductNotFoundException();
        }

        return $juice;
    }

    public function findBy($criteria, $orderby = [])
    {
        $juices = $this->entityMgr->getRepository('NS\Purearth\Product\Juice')->findBy($criteria, $orderby);
        if (!$juices || empty($juices))
        {
            throw new ProductNotFoundException();
        }

        return $juices;
    }

    public function highlights()
    {
        $juices = $this->entityMgr->getRepository('NS\Purearth\Product\Juice')->findBy(array(), array(), 4);
        if (!$juices || empty($juices))
        {
            $juices = array();
        }

        return $juices;
    }
}