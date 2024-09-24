<?php

namespace NS\Purearth\Product;

use Doctrine\ORM\EntityRepository;

class SpecialRepository extends EntityRepository
{
    public function findCurrent()
    {
        return $this->createQueryBuilder('c')
                ->where('c.startDate <= :today')
                ->andWhere('c.endDate >= :today')
                ->orderBy('c.name', 'ASC')
                ->setParameter('today',new \DateTime())
                ->getQuery()->getResult();
    }
}