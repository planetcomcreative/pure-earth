<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 14/07/16
 * Time: 1:54 PM
 */

namespace NS\Purearth\Order\Handler;


use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\Order\Charge;
use NS\Purearth\Order\Command\PlaceOrderCommand;
use NS\Purearth\Order\Event\OrderEvent;
use NS\Purearth\Order\Exceptions\PaymentApiCommunicationException;
use NS\Purearth\Order\Exceptions\PaymentDeclinedException;
use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use NS\PurearthBundle\Service\PaymentProcessorInterface;

class PlaceOrderHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PaymentProcessorInterface
     */
    private $processor;

    private $gst_rate;
    private $pst_rate;
    private $shipping_rate;

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setPaymentProcessor(PaymentProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function setTaxes($gst_rate, $pst_rate)
    {
        $this->gst_rate = $gst_rate;
        $this->pst_rate = $pst_rate;
    }

    public function setShipping($shipping_rate)
    {
        $this->shipping_rate = $shipping_rate;
    }

    public function __invoke(PlaceOrderCommand $command)
    {
        $order = $command->getOrder();
        $description = 'Purearth Organics order #'.$order->getId();

        try
        {
            $order->setDeliveryCharge($this->shipping_rate);
            $order->setPstRate($this->pst_rate);
            $order->setGstRate($this->gst_rate);

            if($order->isChargable())
            {
                $amt    = $order->getChargeTotal();
                $charge = $this->processor->charge($amt, 'cad', $description, $order->getUser()->getEmail());

                $order->pay($charge);
            }
            else
            {
                $charge = new Charge();
                $charge->setChargeId("N\\A");
                $charge->setAmount(0);
                $charge->setCard("N\\A");
                $charge->setLast4(null);
                $charge->setCardHolder(null);
                $charge->setCurrency(null);

                $order->pay($charge);
            }

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->dispatch(OrderEvent::ORDER_PAID, new OrderEvent($order));
        }
        catch(PaymentDeclinedException $e)
        {
            $order->setStatus(OrderStatus::PAYMENT_DECLINED);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            throw $e;
        }
        catch(PaymentApiCommunicationException $e)
        {
            $order->setStatus(OrderStatus::PAYMENT_FAILED);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            throw $e;
        }
        catch(\Exception $e)
        {
            $order->setStatus(OrderStatus::PAYMENT_FAILED);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            throw $e;
        }
    }

}