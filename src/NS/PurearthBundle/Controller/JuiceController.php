<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;
use NS\Purearth\Product\Juice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class JuiceController extends Controller
{
    /**
     * @Route("/cleanses", name="juice_list")
     */
    public function indexAction(Request $request)
    {
        $cart = $this->get('ns_purearth.cart_manager')->getCart();
        $saleService = $this->get('ns_purearth.sale');
        $sales = $saleService->findSales();

        $out = [];
        try
        {
            $juices = $this->get('ns.purearth.juice')->findAll();

            /**
             * @var Juice $juice
             */
            foreach($juices as $juice)
            {
                $name = $juice->getName();
                $price = $juice->getPrice();
                $cat = $juice->getProductCategory() ? $juice->getProductCategory()->getName() : '';

                if(!isset($out[$name]))
                {
                    $out[$name] = ['cat'=>[], 'low'=>999999, 'high'=>0];
                }

                $out[$name]['low'] = $price < $out[$name]['low'] ? $price : $out[$name]['low'];
                $out[$name]['high'] = $price > $out[$name]['high'] ? $price : $out[$name]['high'];
                $out[$name]['cat'][$cat]['juice'] = $juice;
            }
        }
        catch(ProductNotFoundException $e)
        {
            $out = array();
        }

        return $this->render('NSPurearthBundle:Juice:list.html.twig', array('items'=>$out, 'cart'=>$cart, 'sales'=>$sales));
    }

    /**
     * @Route("/juices/view/{pid}", name="juice_show")
     */
    public function viewAction(Request $request, $pid)
    {
        $cart = $this->get('ns_purearth.cart_manager')->getCart();

        try
        {
            $juice = $this->get('ns.purearth.juice')->find($pid);
            $juices = $this->get('ns.purearth.juice')->findBy(['name'=>$juice->getName()], ['price'=>'asc', 'productCategory'=>'asc']);
        }
        catch(ProductNotFoundException $e)
        {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('NSPurearthBundle:Juice:show.html.twig', array('juice'=>$juice, 'juices'=>$juices, 'cart'=>$cart));
    }

    public function highlightsAction()
    {
        $juice = $this->get('ns.purearth.juice')->highlights();

        return $this->render('NSPurearthBundle:Juice:highlights.html.twig', array('juices'=>$juice));
    }
}