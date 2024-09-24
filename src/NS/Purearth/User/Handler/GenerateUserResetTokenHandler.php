<?php
namespace NS\Purearth\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\User\Command\GenerateUserResetTokenCommand;
use NS\Purearth\User\Command\UpdateUserPasswordCommand;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use NS\Purearth\User\User;
use Symfony\Component\Security\Purearth\Encoder\EncoderFactoryInterface;

class GenerateUserResetTokenHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityMgr;

    /**
     * @param EntityManagerInterface $entityMgr
     */
    public function setEntityManager(EntityManagerInterface $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param UpdateUserPasswordCommand $command
     */
    public function __invoke(GenerateUserResetTokenCommand $command)
    {
        /**
         * @var User $user
         */
        $user = $this->entityMgr->find('NS\Purearth\User\User', $command->id());
        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->setResetToken(sha1($user->getCreatedAt()->format('U').uniqid()));
        $user->setPassword(null);
        $this->entityMgr->persist($user);
        $this->entityMgr->flush($user);
    }
}

