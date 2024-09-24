<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 10:43 AM
 */

namespace NS\Purearth\User;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository
{
    /**
     * UserProviderInterface
     *
     * @param string $username
     * @return string
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        try
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.email = :email')
                ->setParameter('email',$username)
                ->getQuery()->getSingleResult();

        }
        catch (UnexpectedResultException $exception) {
            throw new UsernameNotFoundException(sprintf('No record found for user %s', $username), null, $exception);
        }
    }

    public function findByToken($token)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.confirmationToken = :token')
            ->setParameter('token',$token)
            ->getQuery()->getSingleResult();
    }

    public function findByResetToken($token)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.resetToken = :token')
            ->setParameter('token',$token)
            ->getQuery()->getSingleResult();
    }

    public function updateSubscriberHash($id, $hash)
    {
        $this->createQueryBuilder('u')
            ->update()
            ->set('u.mailchimpSubscriberHash', ':hash')
            ->where('u.id = :id')
            ->setParameters([
                'id' => $id,
                'hash' => $hash
            ])
            ->getQuery()->execute();
    }
}
