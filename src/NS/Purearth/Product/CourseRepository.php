<?php

namespace NS\Purearth\Product;

use Doctrine\ORM\EntityRepository;

class CourseRepository extends EntityRepository
{
    public function findCurrent($limit = 0)
    {
        $today = new \DateTime();

        $q = $this->createQueryBuilder('c')
                ->andWhere('c.date >= :today')
                ->andWhere('c.registrationCutoff >= :today')
                ->orderBy('c.date, c.registrationCutoff, c.name', 'ASC')
                ->setParameter('today',$today->format('Y-m-d'));

        if($limit)
        {
            $q->setMaxResults($limit);
        }

        return $q->getQuery()->getResult();
    }
}