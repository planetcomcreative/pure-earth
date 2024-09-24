<?php

namespace NS\PurearthBundle\Test\Service;

use NS\Infrastructure\Security\SecurityUser;
use NS\Purearth\Product\Juice;
use NS\Purearth\User\User;
use NS\PurearthBundle\Service\CartManager;
use NS\PurearthBundle\Service\InMemoryCacheStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $cartManager,
              $tokenStorage,
              $token,
              $cache,
              $session,
              $sessionKey;
    
    protected function setUp()
    {
        $this->cache = new InMemoryCacheStorage('nspurearth_cart_test', 3600);

        $user = new SecurityUser(1,"Smith", "John", "jsmith@example.com", "coconuts", "1234567", false, true);

        $this->token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
                ->disableOriginalConstructor()
                ->getMock();

        $this->token->expects($this->any())
                ->method('getUser')
                ->willReturn($user);

        $this->tokenStorage = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenStorage->expects($this->any())
            ->method('getToken')
            ->willReturn($this->token);

        $this->session = new Session(new MockArraySessionStorage());
        $this->sessionKey = 'ns_purearth.cart_test';


        $this->cartManager = new CartManager($this->session, $this->sessionKey, $this->tokenStorage, $this->cache);
    }

    protected function anonymousSetUp()
    {
        $this->cache = new InMemoryCacheStorage('nspurearth_cart_test', 3600);

        $user = "anon.";

        $this->token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenStorage->expects($this->any())
            ->method('getToken')
            ->willReturn($this->token);

        $this->session = new Session(new MockArraySessionStorage());
        $this->sessionKey = 'ns_purearth.cart_test';


        $this->cartManager = new CartManager($this->session, $this->sessionKey, $this->tokenStorage, $this->cache);
    }

    public function testGetCart()
    {
        $this->assertInstanceOf('NS\Purearth\Order\Cart', $this->cartManager->getCart());
    }

    public function testAddItem()
    {
        $juice = new Juice();
        $juice->setId(64);

        $this->cartManager->addItem($juice);

        $this->assertInstanceOf('NS\Purearth\Order\Cart', $this->cartManager->getCart());
        $this->assertEquals(array(64=>1), $this->cartManager->getCart()->getItems());

        $juice = new Juice();
        $juice->setId(152);

        $this->cartManager->addItem($juice, 3);

        $this->assertInstanceOf('NS\Purearth\Order\Cart', $this->cartManager->getCart());
        $this->assertEquals(array(64=>1, 152=>3), $this->cartManager->getCart()->getItems());


        $this->setExpectedException('InvalidArgumentException', 'Argument 1 passed to CartManager::addItem() must be instance of NS\Purearth\Product\AbstractProduct, or integer');
        $this->cartManager->addItem(null);

    }

    public function testAddItemAcceptsInteger()
    {
        $this->cartManager->addItem(15);

        $this->assertInstanceOf('NS\Purearth\Order\Cart', $this->cartManager->getCart());
        $this->assertEquals(array(15=>1), $this->cartManager->getCart()->getItems());

        $this->cartManager->addItem(9, 5);

        $this->assertInstanceOf('NS\Purearth\Order\Cart', $this->cartManager->getCart());
        $this->assertEquals(array(15=>1, 9=>5), $this->cartManager->getCart()->getItems());
    }

    public function testRemoveItem()
    {
        $juice1 = new Juice();
        $juice1->setId(21);

        $this->cartManager->addItem($juice1, 4);

        $juice2 = new Juice();
        $juice2->setId(5);

        $this->cartManager->addItem($juice2, 6);

        $this->assertEquals(array(21=>4, 5=>6), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem($juice1);

        $this->assertEquals(array(5=>6), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem($juice2);

        $this->assertEquals(array(), $this->cartManager->getCart()->getItems());
    }

    public function testRemoveItemAcceptsInteger()
    {
        $juice1 = new Juice();
        $juice1->setId(5);

        $this->cartManager->addItem($juice1, 3);

        $juice2 = new Juice();
        $juice2->setId(2);

        $this->cartManager->addItem($juice2, 16);

        $this->assertEquals(array(5=>3, 2=>16), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem(5);

        $this->assertEquals(array(2=>16), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem(2);

        $this->assertEquals(array(), $this->cartManager->getCart()->getItems());
    }

    public function testDeleteCart()
    {
        $juice1 = new Juice();
        $juice1->setId(7);

        $this->cartManager->addItem($juice1, 4);

        $juice2 = new Juice();
        $juice2->setId(44);

        $this->cartManager->addItem($juice2, 6);

        $this->assertEquals(array(7=>4, 44=>6), $this->cartManager->getCart()->getItems());

        $this->cartManager->deleteCart();

        $this->assertEquals(array(), $this->cartManager->getCart()->getItems());
    }

    public function testCartPersistence()
    {
        $juice1 = new Juice();
        $juice1->setId(7);

        $this->cartManager->addItem($juice1, 4);

        $juice2 = new Juice();
        $juice2->setId(44);

        $this->cartManager->addItem($juice2, 6);

        $this->cartManager = new CartManager($this->session, $this->sessionKey, $this->tokenStorage, $this->cache);

        $this->assertEquals(array(7=>4, 44=>6), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem($juice1);

        $this->cartManager = new CartManager($this->session, $this->sessionKey, $this->tokenStorage, $this->cache);

        $this->assertEquals(array(44=>6), $this->cartManager->getCart()->getItems());

        $this->cartManager->removeItem($juice2);

        $this->cartManager = new CartManager($this->session, $this->sessionKey, $this->tokenStorage, $this->cache);

        $this->assertEquals(array(), $this->cartManager->getCart()->getItems());
    }

    public function testAnonymousCart()
    {
        $this->anonymousSetUp();

        $juice1 = new Juice();
        $juice1->setId(15);

        $this->cartManager->addItem($juice1, 7);

        $this->assertEquals(array(15=>7), $this->cartManager->getCart()->getItems());
    }
}
