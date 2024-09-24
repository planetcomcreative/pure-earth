<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 29/08/16
 * Time: 6:30 PM
 */

namespace NS\Purearth\User\Handler;


use Doctrine\ORM\UnexpectedResultException;
use NS\Purearth\AbstractCommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\User\Command\ConfirmUserCommand;
use NS\Purearth\User\Exceptions\UserNotFoundException;

class ConfirmUserHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityMgr
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ConfirmUserCommand $command)
    {
        $token = $command->getToken();

        try
        {
            $user = $this->entityManager->getRepository('NSPurearthBundle:User\User')->findByToken($token);
        }
        catch(UnexpectedResultException $e)
        {
            throw new UserNotFoundException('User not found');
        }

        $user->setConfirmed(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}