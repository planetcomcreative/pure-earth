<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 12:17 PM
 */

namespace NS\Purearth\Order\Command;


use NS\Purearth\Order\CartInterface;
use NS\Purearth\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateOrderFromCartCommand
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var CartInterface
     */
    protected $cart;

    public function __construct($user, CartInterface $cart)
    {
        $this->user = $user;
        $this->cart = $cart;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        return $this->cart;
    }
}