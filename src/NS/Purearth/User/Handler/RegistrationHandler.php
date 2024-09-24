<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 20/07/16
 * Time: 11:20 AM
 */

namespace NS\Purearth\User\Handler;


use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\User\Command\RegisterUserCommand;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RegistrationHandler extends AbstractCommandHandler
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
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

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param RegisterUserCommand $command
     */
    public function __invoke(RegisterUserCommand $command)
    {
        $user = new User();
        $user->setLastName($command->getLastName());
        $user->setFirstName($command->getFirstName());
        $user->setFirstName($command->getFirstName());
        $user->setEmail($command->getEmail());
        $user->setAddrStreet($command->getAddrStreet());
        $user->setAddrPostal($command->getAddrPostal());
        $user->setPrimaryPhone($command->getPrimaryPhone());
        $user->setAddrCity($command->getAddrCity());

        $encoder = $this->encoderFactory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($command->getPassword(), $user->getSalt()));

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        $this->dispatch(UserEvent::USER_REGISTERED, new UserEvent($user));
    }
}
