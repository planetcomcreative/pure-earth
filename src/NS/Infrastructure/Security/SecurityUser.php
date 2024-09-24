<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 12:05 PM
 */

namespace NS\Infrastructure\Security;


use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface, EquatableInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var boolean
     */
    protected $admin;

    protected $phone;

    /**
     * $var boolean
     */
    protected $confirmed;
    protected $mailchimpSubscriberHash;
    protected $isSubscribed;

    public function __construct($id, $lastName, $firstName, $email, $password, $salt, $admin, $confirmed, $mailchimpSubscriberHash, $isSubscribed, $phone)
    {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
        $this->admin = $admin;
        $this->confirmed = $confirmed;
        $this->mailchimpSubscriberHash = $mailchimpSubscriberHash;
        $this->isSubscribed = $isSubscribed;
        $this->phone = $phone;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    public function getRoles()
    {
        $roles = array('ROLE_CUSTOMER');

        if($this->admin)
        {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        if($this->confirmed)
        {
            $roles[] = 'ROLE_CONFIRMED_USER';
        }

        return $roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof User) {
            // Check that the roles are the same, in any order
            return $this->isConfirmed() == $user->isConfirmed() && $this->getId() == $user->getId();
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getMailchimpSubscriberHash()
    {
        return $this->mailchimpSubscriberHash;
    }

    /**
     * @return mixed
     */
    public function getisSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
