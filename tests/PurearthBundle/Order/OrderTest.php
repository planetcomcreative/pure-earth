<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 05/08/16
 * Time: 11:19 AM
 */

namespace NS\PurearthBundle\Test\Order;


use NS\Purearth\Order\Cart;
use NS\Purearth\Order\Charge;
use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use NS\Purearth\Product\Exceptions\ProductNotFoundException;
use NS\Purearth\Product\Juice;
use NS\Purearth\User\User;
use Doctrine\Common\Collections\Criteria;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    protected $j1, $j2, $j3, $user, $refs;

    protected function setUp()
    {
        $this->user = new User();
        $this->user->setId(1);

        $this->j1 = new Juice();
        $this->j1->setId(5);
        $this->j1->setName('Test Product 1');
        $this->j1->setPrice(3.99);
        $this->j1->setGst(true);
        $this->j1->setPst(false);

        $this->j2 = new Juice();
        $this->j2->setId(3);
        $this->j2->setName('Test Product 2');
        $this->j2->setPrice(13.99);
        $this->j2->setGst(false);
        $this->j2->setPst(true);

        $this->refs[$this->j1->getId()] = $this->j1;
        $this->refs[$this->j2->getId()] = $this->j2;
    }

    public function testPopulateOrderFromCart()
    {
        $cart = new Cart();
        $cart->addItem($this->j1);
        $cart->addItem($this->j2, 2);

        $order = new Order($this->user);

        $order->populateFromCart($cart, $this->refs);

        $ops = $order->getOrderProducts();

        $prods = array();
        foreach($ops as $op)
        {
            $prods[$op->getProduct()->getId()] = $op->getQuantity();
        }

        $this->assertEquals(array(5=>1, 3=>2), $prods);
    }

    public function testGst()
    {
        $cart = new Cart();
        $cart->addItem($this->j1);
        $cart->addItem($this->j2, 2);

        $order = new Order($this->user);

        $order->populateFromCart($cart, $this->refs);

        $this->assertEquals(0.4, $order->getGst(0.10));
        $this->assertEquals(32.37, $order->getTotal(0.10, 0));
    }

    public function testPst()
    {
        $cart = new Cart();
        $cart->addItem($this->j1);
        $cart->addItem($this->j2, 2);

        $order = new Order($this->user);

        $order->populateFromCart($cart, $this->refs);

        $this->assertEquals(2.80, $order->getPst(0.10));
        $this->assertEquals(34.77, $order->getTotal(0, 0.10));
    }

    public function testShipping()
    {
        $cart = new Cart();
        $cart->addItem($this->j1);
        $cart->addItem($this->j2, 2);

        $order = new Order($this->user);

        $order->populateFromCart($cart, $this->refs);

        $order->setDeliveryCharge(20.00);
        $order->setDelivery(true);

        $this->assertEquals(51.97, $order->getChargeTotal());
    }

    public function testPay()
    {
        $order = new Order($this->user);
        $charge = new Charge();

        $charge->setChargeId('abcd');
        $charge->setAmount(10.00);
        $charge->setCard('Visa');
        $charge->setCardHolder('John Smith');
        $charge->setCurrency('CAD');
        $charge->setLast4(4444);

        $order->pay($charge);

        $this->assertEquals($order->getPayment()->getChargeId(), $charge->getChargeId());
        $this->assertEquals($order->getPayment()->getCard(), $charge->getCard());
        $this->assertEquals($order->getPayment()->getCardholder(), $charge->getCardHolder());
        $this->assertEquals($order->getPayment()->getCurrency(), $charge->getCurrency());
        $this->assertEquals($order->getPayment()->getLast4(), $charge->getLast4());
        $this->assertEquals($order->getStatus(), OrderStatus::PAID);
    }
}