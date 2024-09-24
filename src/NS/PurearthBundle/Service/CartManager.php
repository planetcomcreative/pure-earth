<?php

namespace NS\PurearthBundle\Service;

use InvalidArgumentException;
use Doctrine\Common\Cache\PhpFileCache;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use NS\Purearth\Order\Cart;
use NS\Purearth\Product\AbstractProduct;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CartManager
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var PhpFileCache|CartStorageInterface
     */
    private $cache;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var string
     */
    private $sessionKey;

    /**
     * CartManager constructor.
     * @param TokenStorage $tokenStorage
     * @param PhpFileCache $cache
     * @param integer $cart_lifetime
     */
    public function __construct(Session $session, $sessionKey, TokenStorage $tokenStorage, CartStorageInterface $cache)
    {
        $this->session       = $session;
        $this->tokenStorage  = $tokenStorage;
        $this->cache         = $cache;
        $this->sessionKey    = $sessionKey;
    }

    /**
     * @return Cart
     */
    protected function initCart()
    {
        $sessCart = $this->session->get($this->sessionKey, false);

        if($sessCart)
        {
            $cart = $sessCart;
        }
        else
        {
            $cart = $this->cache->get($this->getUserId());
        }

        $this->cart = (!$cart || !($cart instanceof Cart)) ? new Cart():$cart;
    }

    protected function saveCart()
    {
        $this->cache->save($this->getUserId(), $this->cart);

        $this->session->set($this->sessionKey, $this->cart);
    }

    public function deleteCart()
    {
        $this->cache->delete($this->getUserId());

        $this->session->remove($this->sessionKey);

        $this->initCart();
    }

    /**
     * @return User
     */
    protected function getUserId()
    {
        if($this->userId) {
           return $this->userId;
        }

        $token = $this->tokenStorage->getToken();

        if($token)
        {
            $user = $token->getUser();

            if($user instanceof UserInterface)
            {
                $this->userId = $user->getId();
                return $this->userId;
            }
        }

        $cid  = $this->session->get('cart_id', hash('sha256', $this->session->getId()));
        $this->session->set('cart_id', $cid);

        return $cid;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        if($this->cart === null) {
            $this->initCart();
        }

        return $this->cart;
    }

    /**
     * @param AbstractProduct|integer $item
     */
    public function addItem($item, $qty = 1)
    {
        if(!is_integer($item) && !($item instanceof AbstractProduct))
        {
            throw new InvalidArgumentException('Argument 1 passed to CartManager::addItem() must be instance of NS\Purearth\Product\AbstractProduct, or integer');
        }

        if($this->cart === null) {
            $this->initCart();
        }

        $this->cart->addItem($item, $qty);

        $this->saveCart();
    }

    /**
     * @param AbstractProduct|integer $item
     */
    public function removeItem($item)
    {
        if($this->cart === null) {
            $this->initCart();
        }

        $this->cart->removeItem($item);

        $this->saveCart();
    }
}