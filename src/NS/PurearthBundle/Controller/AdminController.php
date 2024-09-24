<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Order\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function indexAction(Request $request)
    {
        $orders = $this->get('ns.purearth.order')->findAll(10, OrderStatus::PAID);
        $users = $this->get('ns.purearth.user')->findAll(10);

        return $this->render('NSPurearthBundle:Admin:index.html.twig', ['orders'=>$orders, 'users'=>$users]);
    }

    /**
     * @Route("/login", name="admin_login")
     */
    public function adminLoginAction(Request $request)
    {
//        $helper = $this->get('security.authentication_utils');
//
//        return $this->render('NSPurearthBundle:Admin:login.html.twig', [
//            'last_username' => $helper->getLastUsername(),
//            'error' => $helper->getLastAuthenticationError(),
//        ]);
    }
}
