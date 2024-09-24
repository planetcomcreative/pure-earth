<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;
use NS\PurearthBundle\Entity\CourseContact;
use NS\PurearthBundle\Form\CourseContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends Controller
{
    /**
     * @Route("/classes", name="course_list")
     */
    public function indexAction(Request $request)
    {
        try
        {
            $courses = $this->get('ns.purearth.course')->getCurrent();
        }
        catch(ProductNotFoundException $e)
        {
            $courses = array();
        }

        $form = $this->createForm(CourseContactType::class);

        return $this->render('NSPurearthBundle:Course:list.html.twig', array('items'=>$courses, 'form'=>$form->createView()));
    }

    public function highlightAction(Request $request)
    {
        try
        {
            $courses = $this->get('ns.purearth.course')->getCurrent(3);
        }
        catch(ProductNotFoundException $e)
        {
            $courses = array();
        }

        return $this->render('NSPurearthBundle:Course:highlight.html.twig', array('courses'=>$courses));
    }

    /**
     * @param Request $request
     * @Route("/classes/view/{id}", name="course_view")
     */
    public function showAction(Request $request, $id)
    {
        $cart = $this->get('ns_purearth.cart_manager')->getCart();

        try
        {
            $course = $this->get('ns.purearth.course')->find($id);
        }
        catch(ProductNotFoundException $e)
        {
            throw $this->createNotFoundException('Class not found');
        }

        if(!$course->isAvailable())
        {
            throw $this->createNotFoundException('The product was not found.');
        }

        return $this->render('NSPurearthBundle:Course:show.html.twig', array('course'=>$course, 'cart'=>$cart));
    }

    /**
     * @param Request $request
     * @Route("/classes/contact", name="course_contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(CourseContactType::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            /**
             * @var CourseContact $contact
             */
            $contact = $form->getData();

            $message = \Swift_Message::newInstance()
                    ->setSubject('New Class Request')
                    ->setFrom($this->getParameter('email_sender'))
                    ->setTo($this->getParameter('course_contact_email'))
                    ->setBody('From: '.$contact->getEmail().'
                    
                    '.$contact->getText());

            $this->get('mailer')->send($message);

            $this->get('ns_flash')->addSuccess(null, "Thanks!", "Your message has been received!");
        }
        else {
            $this->get('ns_flash')->addError(null, "Sorry", "Some required information is missing");
        }
        return $this->redirectToRoute('course_list');
    }
}