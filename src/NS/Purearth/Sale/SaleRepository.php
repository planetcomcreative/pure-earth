<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/05/17
 * Time: 11:46 AM
 */

namespace NS\Purearth\Sale;

use Doctrine\ORM\EntityRepository;
use \DateTime;

class SaleRepository extends EntityRepository
{
    public function findSales()
    {
        return $this->createQueryBuilder('s')
                ->where('s.startDate <= :startdate AND s.endDate >= :enddate')
                ->orderBy('s.startDate', 'ASC')
                ->setParameters([
                    'startdate' => DateTime::createFromFormat('h:i:s', '00:00:00'),
                    'enddate' => DateTime::createFromFormat('h:i:s', '11:59:59')
                ])
                ->getQuery()->getResult();
    }
}