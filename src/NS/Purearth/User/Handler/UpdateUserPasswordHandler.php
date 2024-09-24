<?php
namespace NS\Purearth\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\User\Command\UpdateUserPasswordCommand;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UpdateUserPasswordHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityMgr;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EntityManagerInterface $entityMgr
     */
    public function setEntityManager($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory($encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param UpdateUserPasswordCommand $command
     */
    public function __invoke(UpdateUserPasswordCommand $command)
    {
        $user = $this->entityMgr->find('NS\Purearth\User\User', $command->id());
        if (!$user) {
            throw new UserNotFoundException();
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($command->password(), $user->getSalt()));
        $this->entityMgr->persist($user);
        $this->entityMgr->flush($user);
        $this->dispatch(UserEvent::PASSWORD_UPDATED, new UserEvent($user));
    }
}

