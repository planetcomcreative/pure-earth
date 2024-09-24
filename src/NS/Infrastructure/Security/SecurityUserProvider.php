<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 12:06 PM
 */

namespace NS\Infrastructure\Security;


use Doctrine\ORM\EntityRepository;
use NS\Purearth\User\User;
use NS\PurearthBundle\Service\MailchimpAPI;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityUserProvider implements UserProviderInterface
{
    /**
     * @var MailchimpAPI
     */
    protected $mailchimp;
    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * SecurityUserProvider constructor.
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityRepository $userRepository, MailchimpAPI $mailchimp)
    {
        $this->userRepository = $userRepository;
        $this->mailchimp = $mailchimp;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user  = $this->userRepository->loadUserByUsername($username);

        $subscriber = $this->mailchimp->quickGetSubscriber($user->getEmail());
        $isSubscribed = (isset($subscriber['status']) && $subscriber['status'] === 'subscribed');

        return new SecurityUser(
            $user->getId(),
            $user->getLastName(),
            $user->getFirstName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getSalt(),
            $user->isAdmin(),
            $user->isConfirmed(),
            $this->mailchimp->getSubscriberHash($user->getEmail()),
            $isSubscribed,
            $user->getPrimaryPhone()
        );
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getEmail());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $class == 'NS\Infrastructure\Security\SecurityUser';
    }
}
