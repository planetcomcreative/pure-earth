<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/07/16
 * Time: 4:23 PM
 */

namespace NS\PurearthBundle\Controller;


use NS\Purearth\Order\Command\CourseRegistrationCommand;
use NS\Purearth\Order\Command\PlaceOrderCommand;
use NS\Purearth\Order\Exceptions\OrderNotFoundException;
use NS\Purearth\Order\Exceptions\PaymentApiCommunicationException;
use NS\Purearth\Order\Order;
use NS\Purearth\Product\Exceptions\CourseUnavailableException;
use NS\PurearthBundle\Form\CheckoutType;
use NS\StripeBundle\Form\CreditCardType;
use NS\Purearth\Order\Command\CreateOrderFromCartCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use NS\Purearth\Order\Exceptions\PaymentDeclinedException;

/**
 * Class OrderController
 * @package NS\PurearthBundle\Controller
 *
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkoutAction(Request $request)
    {
        /**
         * @var Order $activeOrder
         */
        $activeOrder = $this->get('ns.purearth.order')->getActiveOrder();
        $stripe      = $this->get('ns.stripe');
        $shipping    = $this->getParameter('shipping_rate');
        $user = $this->get('ns.purearth.user')->find($this->getUser()->getId());

        if(!$activeOrder)
        {
            $cart = $this->get('ns_purearth.cart_manager')->getCart();

            $orderCommand = new CreateOrderFromCartCommand($user, $cart);

            $this->get('command_bus')->handle($orderCommand);

            $activeOrder = $this->get('ns.purearth.order')->getActiveOrder();
        }

        if(!count($activeOrder->getOrderProducts()))
        {
            return $this->redirectToRoute('cart_view');
        }

        $gst_rate = $this->getParameter('gst_rate');
        $pst_rate = $this->getParameter('pst_rate');
        $subtotal = $activeOrder->getSubtotal();
        $gst      = $activeOrder->getGst($gst_rate);
        $pst      = $activeOrder->getPst($pst_rate);
        $total    = $activeOrder->getTotal($gst_rate, $pst_rate);

        $cc_form = $this->createForm(CreditCardType::class)->createView();

        $checkout_form = $this->createForm(CheckoutType::class, null, array('user'=>$user, 'order'=>$activeOrder));
        $checkout_form->handleRequest($request);

        if($checkout_form->isValid())
        {
            $activeOrder = $this->get('ns.purearth.order')->getActiveOrder();
            $user = $this->get('ns.purearth.user')->find($this->getUser()->getId());
            $registrationCommands = array();

            $data = $checkout_form->getData();

            $reg = $request->request->get('checkout');

            if(isset($reg['registrations']))//This is unmapped
            {
                foreach ($reg['registrations']['courses'] as $course)
                {
                    $registrationCommands[] = new CourseRegistrationCommand($course, $activeOrder, $user);
                }
            }

            if(isset($data['delivery']['delivery']))
            {
                $activeOrder->setDelivery($data['delivery']['delivery']);
                $activeOrder->setDeliveryAddress($data['delivery']['name'] . "\n" . $data['delivery']['address'] . "\n" . $data['delivery']['postalCode'] . "\n" . $data['delivery']['phone']);
            }

            if(isset($data['comments']))
            {
                $activeOrder->setComments($data['comments']);
            }

            $orderCommand = new PlaceOrderCommand($activeOrder, 'cad');

            try
            {
                foreach($registrationCommands as $regCommand)
                {
                    $this->get('command_bus')->handle($regCommand);
                }

                $this->get('command_bus')->handle($orderCommand);
                $this->get('ns_purearth.cart_manager')->deleteCart();

                $this->get('session')->set('complete_order_id', $activeOrder->getId());
                $this->get('ns_flash')->addSuccess(null, 'Thank you!', 'Your order has been processed!');

                return $this->redirectToRoute('checkout_complete');
            }
            catch(CourseUnavailableException $e)
            {
                $this->get('ns_flash')->addError(null, 'Class full.', 'Your order could not be processed because one or more of the classes you attempted to register for is no longer available. If you are attempting to book more than one spot in a class, please ensure that the class has more than one spot available.');

                return $this->redirectToRoute('checkout');
            }
            catch(PaymentDeclinedException $e)
            {
                $this->get('ns_flash')->addError(null, 'Payment Declined', $e->getDeclineData()['message']);

                return $this->redirectToRoute('checkout');
            }
            catch(PaymentApiCommunicationException $e)
            {
                $this->get('ns_flash')->addError(null, 'Payment Failed', 'There was an error communicating with the payment processor.  Please try again later.');

                return $this->redirectToRoute('checkout');
            }
        }
        else if($checkout_form->isSubmitted())
        {
            $this->get('ns_flash')->addError(null, 'Some required information is missing', 'Please double-check and try again.');
        }

        return $this->render('NSPurearthBundle:Order:checkout.html.twig', array('order'=>$activeOrder, 'subtotal'=>$subtotal, 'gst'=>$gst, 'pst'=>$pst, 'total'=>$total, 'cc_form'=>$cc_form, 'public_key'=>$stripe->getPublicKey(), 'shipping'=>$shipping, 'checkout_form'=>$checkout_form->createView()));
    }

    /**
     * @Route("/checkout/complete", name="checkout_complete")
     * @Route("/details/{orderId}", name="order_details")
     * @param Request $request
     * @param $orderId
     */
    public function completeAction(Request $request, $orderId = false)
    {
        $newOrder = false;
        $oid = $this->get('session')->get('complete_order_id', false);
        $this->get('session')->remove('complete_order_id');

        if($oid)
        {
            $newOrder = true;
        }

        $orderId = $oid ? $oid : $orderId;

        if(!$orderId)
        {
            return $this->redirectToRoute('customer_dashboard');
        }

        $shipping    = $this->getParameter('shipping_rate');

        try
        {
            $order = $this->get('ns.purearth.order')->findWithUser($this->getUser(), $orderId);
        }
        catch(OrderNotFoundException $e)
        {
            return $this->createNotFoundException('This order could not be found');
        }

        $gst_rate = $this->getParameter('gst_rate');
        $pst_rate = $this->getParameter('pst_rate');
        $subtotal = $order->getSubtotal();
        $gst      = $order->getGst($gst_rate);
        $pst      = $order->getPst($pst_rate);
        $total    = $order->getTotal($gst_rate, $pst_rate);

        return $this->render('NSPurearthBundle:Order:complete.html.twig', array('order'=>$order, 'newOrder'=>$newOrder, 'subtotal'=>$subtotal, 'gst'=>$gst, 'pst'=>$pst, 'total'=>$total, 'shipping'=>$shipping));
    }
}
