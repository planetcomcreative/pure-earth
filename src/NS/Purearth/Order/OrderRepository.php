<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 4:53 PM
 */

namespace NS\Purearth\Order;


use Doctrine\ORM\EntityRepository;
use NS\Purearth\User\User;

class OrderRepository extends EntityRepository
{
    /**
     * @param $user
     * @return User | integer
     */
    public function findOpen($user)
    {
        return $this->createQueryBuilder('o')
                ->andWhere('o.user = :user')
                ->andWhere('o.status = :status')
                ->setParameters(array(
                    'user' => $user,
                    'status' => OrderStatus::OPEN
                ))
                ->orderBy('o.date', 'desc')
                ->getQuery()->getSingleResult();
    }
}