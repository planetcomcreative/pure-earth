<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 13/03/19
 * Time: 3:33 PM
 */

namespace NS\PurearthBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewsletterController
 * @package NS\PurearthBundle\Controller
 *
 * @Route("/newsletter")
 */
class NewsletterController extends Controller
{
    /**
     * @param Request $request
     * @param $type
     * @param $wasTriggered
     * @Route("/new", name="logNewsletter")
     */
    public function logAction(Request $request)
    {
        $this->get('ns.purearth.signup')->logSignup($request->request->get('type'), $request->request->get('wasTriggered'));

        return new Response('OK');
    }

    /**
     * @param Request $request
     *
     * @Route("/disable", name="disablePrompt")
     */
    public function disablePromptAction(Request $request)
    {
        $this->get('session')->set('disablePrompt', true);

        return new Response('OK');
    }
}
