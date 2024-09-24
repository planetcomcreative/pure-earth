<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/07/16
 * Time: 4:34 PM
 */

namespace NS\Purearth\Order\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use NS\Purearth\Order\Exceptions\OrderNotFoundException;
use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use NS\Purearth\User\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderService
{
    protected $entityMgr;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityMgr)
    {
        $this->entityMgr  = $entityMgr;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function getActiveOrder()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if(!($user instanceof UserInterface))
        {
            return false;
        }

        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:Order\Order')->findOpen($user->getId());
        }
        catch(NoResultException $e)
        {
            return false;
        }
    }

    public function findAll($limit = 0, $status = false)
    {
        try
        {
            $criteria = [];

            if($status)
            {
                $criteria = ['status'=>$status];
            }
            return $this->entityMgr->getRepository('NSPurearthBundle:Order\Order')->findBy($criteria, array('date'=>'desc'), $limit ? $limit : null);
        }
        catch(NoResultException $e)
        {
            return new OrderNotFoundException($e->getMessage());
        }
    }

    public function find($orderId)
    {
        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:Order\Order')->find($orderId);
        }
        catch(NoResultException $e)
        {
            return new OrderNotFoundException($e->getMessage());
        }
    }

    public function findWithUser($user, $orderId = false)
    {
        try
        {
            return $orderId ? $this->entityMgr->getRepository('NSPurearthBundle:Order\Order')->findOneBy(array('id'=>$orderId, 'user'=>$user->getId(), 'status'=>OrderStatus::PAID)) : $this->entityMgr->getRepository('NSPurearthBundle:Order\Order')->findBy(array('user'=>$user, 'status'=>OrderStatus::PAID), array('date'=>'desc'));
        }
        catch(NoResultException $e)
        {
            return new OrderNotFoundException($e->getMessage());
        }
        catch(NonUniqueResultException $e)
        {
            return new OrderNotFoundException($e->getMessage());
        }
    }
}