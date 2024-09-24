<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Order\Command\DeleteOrderCommand;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Product\Exceptions\ProductNotFoundException;
use NS\Purearth\Product\Exceptions\ProductUnavailableException;
use NS\Purearth\Product\Juice;
use NS\Purearth\Product\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartController
 * @package NS\PurearthBundle\Controller
 *
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/add/{pid}", name="cart_add")
     */
    public function addAction(Request $request, $pid)
    {
        $this->resetOrder();
        try
        {
            $product = $this->get('ns.purearth.product')->find($pid);
        }
        catch(ProductNotFoundException $e)
        {
            return $this->createNotFoundException('Product not found.');
        }

        $manager = $this->get('ns_purearth.cart_manager');

        try
        {
            $manager->addItem($product);
        }
        catch(ProductUnavailableException $e)
        {
            $response = new JsonResponse(null, 410); //Ajax handler listens for the 410 response to throw a "No longer available" message
            return $response;
        }

        $cart = $manager->getCart();
        $res = array('items'=>$cart->getItems(), 'count'=>$cart->getCount());

        $response = new JsonResponse($res);
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    /**
     * @Route("/delete/{pid}", name="cart_delete")
     */
    public function deleteAction(Request $request, $pid)
    {
        $this->resetOrder();
        try
        {
            $product = $this->get('ns.purearth.product')->find($pid);
        }
        catch(ProductNotFoundException $e)
        {
            return $this->createNotFoundException('Product not found.');
        }

        $manager = $this->get('ns_purearth.cart_manager');

        $manager->removeItem($product);

        $cartDetails = $this->get('ns_purearth.cart_detail')->getDetails();

        $res = array(
            'items'     => $manager->getCart()->getItems(),
            'count'     => $manager->getCart()->getCount(),
            'subtotal'  => $cartDetails->getTotal(),
            'gst'       => $cartDetails->getGst(),
            'grandTotal'=> $cartDetails->getGrandTotal()
        );

        $response = new JsonResponse($res);
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    /**
     * @Route("/empty", name="cart_empty")
     */
    public function emptyAction(Request $request)
    {
        $this->resetOrder();
        $manager = $this->get('ns_purearth.cart_manager');
        $manager->deleteCart();

        $cartDetails = $this->get('ns_purearth.cart_detail')->getDetails();

        $res = array(
            'items'     => $manager->getCart()->getItems(),
            'count'     => $manager->getCart()->getCount(),
            'subtotal'  => $cartDetails->getTotal(),
            'gst'       => $cartDetails->getGst(),
            'grandTotal'=> $cartDetails->getGrandTotal()
        );

        $response = new JsonResponse($res);
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    /**
     * @Route("/update", name="cart_update")
     */
    public function updateAction(Request $request)
    {
        $this->resetOrder();
        $quants = $request->get('cart_quant');
        $manager = $this->get('ns_purearth.cart_manager');

        try
        {
            $products = $this->get('ns.purearth.product')->findBy(['id' => array_keys($quants)]);
        }
        catch(ProductNotFoundException $e)
        {
            $response = new JsonResponse(null, 410); //Ajax handler listens for the 410 response to throw a "No longer available" message
            return $response;
        }

        /**
         * @var AbstractProduct[] $products
         */
        foreach($products as $product)
        {
            $manager->addItem($product, $quants[$product->getId()]);
        }

        $cartDetails = $this->get('ns_purearth.cart_detail')->getDetails();

        $res = array(
            'items'     => $manager->getCart()->getItems(),
            'count'     => $manager->getCart()->getCount(),
            'subtotal'  => number_format($cartDetails->getTotal(), 2),
            'gst'       => number_format($cartDetails->getGst(), 2),
            'grandTotal'=> number_format($cartDetails->getGrandTotal(), 2)
        );

        $response = new JsonResponse($res);
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    /**
     * @Route("/quick", name="cart_quick_view")
     */
    public function quickViewAction(Request $request)
    {
        $cart = $this->get('ns_purearth.cart_manager')->getCart();

        $response = new Response($this->renderView('NSPurearthBundle:Cart:quick.html.twig', array('cart'=>$cart)));
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    /**
     * @Route("/view", name="cart_view")
     */
    public function viewAction(Request $request)
    {
        $cart  = $this->get('ns_purearth.cart_manager')->getCart();

        $cartDetails = $this->get('ns_purearth.cart_detail')->getDetails();

        $response = new Response($this->renderView('NSPurearthBundle:Cart:view.html.twig', array('cart'=>$cart, 'details'=>$cartDetails)));
        $response->headers->add(array(
            'Cache-Control'=>'no-cache, no-store,must-revalidate',
            'Pragma'=>'no-cache',
            'Expires' => 0
        ));

        return $response;
    }

    protected function resetOrder()
    {
        $activeOrder = $this->get('ns.purearth.order')->getActiveOrder();

        if($activeOrder)
        {
            $orderCommand = new DeleteOrderCommand($activeOrder);

            $this->get('command_bus')->handle($orderCommand);
        }
    }
}
