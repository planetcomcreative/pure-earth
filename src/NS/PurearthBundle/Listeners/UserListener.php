<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 20/07/16
 * Time: 11:59 AM
 */

namespace NS\PurearthBundle\Listeners;


use Doctrine\ORM\EntityManager;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\Service\UserService;
use NS\PurearthBundle\Service\Email;
use NS\PurearthBundle\Service\MailchimpAPI;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserListener
{
    /**
     * @var MailchimpAPI
     */
    protected $mailchimp;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var EntityManager
     */
    protected $entity_manager;
    /**
     * @var UserService
     */
    protected $user_service;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Email
     */
    private $email;

    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $requestStack, EventDispatcherInterface $dispatcher, MailchimpAPI $mailchimp, LoggerInterface $logger, UserService $user_service)
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->dispatcher = $dispatcher;
        $this->mailchimp = $mailchimp;
        $this->logger = $logger;
        $this->user_service = $user_service;
    }

    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function onUserRegistered(UserEvent $event)
    {
        $user = $event->getUser();

        $this->email->sendWelcomeEmail($user);
        $this->updateMailingList($event);

    }

    public function onUserUpdated(UserEvent $event)
    {
        $this->updateMailingList($event);
    }

    public function updateMailingList(UserEvent $event, $force = false)
    {
        $user = $event->getUser();

        try
        {
            $response = $this->mailchimp->quickAddOrUpdateSubscriber($user->getEmail(), $user->getMailchimpSubscriberHash(), $user, $force ? true : $user->getForceResubscribe());
        }
        catch(\Throwable $e)
        {
            $this->logger->critical('Exception from Mailchimp API', ['exception'=>$e]);
        }

        if($response && $response['status'] && $response['status'] == 'subscribed' && $response['id'])
        {
            $this->user_service->updateSubscriberHash($user->getId(), $response['id']);
        }
    }

    public function onUserResubscribe(UserEvent $event)
    {
        $this->updateMailingList($event, true);
    }
}
