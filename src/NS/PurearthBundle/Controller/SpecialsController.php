<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SpecialsController extends Controller
{
    /**
     * @Route("/specials", name="specials_list")
     */
    public function indexAction(Request $request)
    {
        try
        {
            $specials = $this->get('ns.purearth.special')->getCurrent();
        }
        catch(ProductNotFoundException $e)
        {
            $specials = array();
        }

        return $this->render('NSPurearthBundle:Special:list.html.twig', array('items'=>$specials));
    }
}