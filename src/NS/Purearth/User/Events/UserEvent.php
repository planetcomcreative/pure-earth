<?php
namespace NS\Purearth\User\Events;

use NS\Purearth\User\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    const PASSWORD_UPDATED = 'user.password_updated';
    const USER_UPDATED = 'user.updated';
    const USER_REGISTERED = 'user.registered';
    const USER_RESUBSCRIBE = 'user.resubscribe';

    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

