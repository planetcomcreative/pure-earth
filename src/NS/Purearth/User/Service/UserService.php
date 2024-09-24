<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/07/16
 * Time: 4:34 PM
 */

namespace NS\Purearth\User\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnexpectedResultException;
use NS\Purearth\Order\Exceptions\OrderNotFoundException;
use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use NS\Purearth\User\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserService
{
    protected $entityMgr;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityMgr)
    {
        $this->entityMgr  = $entityMgr;
        $this->tokenStorage = $tokenStorage;
    }

    public function findAll($limit = 0)
    {
        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:User\User')->findBy(['admin'=>false], ['createdAt'=>'desc'], $limit);
        }
        catch(NoResultException $e)
        {
            return false;
        }
    }

    public function find($id)
    {
        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:User\User')->find($id);
        }
        catch(NoResultException $e)
        {
            throw new UserNotFoundException('User not found');
        }
    }

    public function findByUsername($username)
    {
        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:User\User')->loadUserByUsername($username);
        }
        catch(UsernameNotFoundException $e)
        {
            throw new UserNotFoundException('User not found');
        }
    }

    public function findByResetToken($token)
    {
        try
        {
            return $this->entityMgr->getRepository('NSPurearthBundle:User\User')->findByResetToken($token);
        }
        catch(UnexpectedResultException $e)
        {
            throw new UserNotFoundException('User not found');
        }
    }

    public function updateSubscriberHash($id, $hash)
    {
        $this->entityMgr->getRepository(User::class)->updateSubscriberHash($id, $hash);
    }
}
