<?php
namespace NS\Purearth\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\User\Command\UpdateUserCommand;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UpdateUserHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityMgr;

    /**
     * @param EntityManagerInterface $entityMgr
     */
    public function setEntityManager($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param UpdateUserCommand $command
     */
    public function __invoke(UpdateUserCommand $command)
    {
        $user = $this->entityMgr->find('NS\Purearth\User\User', $command->getId());
        if (!$user) {
            throw new UserNotFoundException();
        }
        
        $data = $command->getAllData();
        $user->updateProfile($data);
        
        $this->entityMgr->persist($user);
        $this->entityMgr->flush($user);
        $this->dispatch(UserEvent::USER_UPDATED, new UserEvent($user));
    }
}