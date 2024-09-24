<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/08/16
 * Time: 3:39 PM
 */

namespace NS\PurearthBundle\Service;


use NS\Purearth\Order\Order;
use NS\Purearth\User\User;

class Email
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $email_sender;

    /**
     * @var string
     */
    protected $order_email_receiver;

    /**
     * @var float
     */
    protected $shipping_rate;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, $email_sender, $order_email_receiver, $shipping_rate)
    {
        $this->twig = $twig;
        $this->email_sender = $email_sender;
        $this->mailer = $mailer;
        $this->order_email_receiver = $order_email_receiver;
        $this->shipping_rate = $shipping_rate;
    }

    public function sendWelcomeEmail(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome to Purearth Organics!')
            ->setFrom($this->email_sender)
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('NSPurearthBundle:Email:welcome.html.twig', array('user'=>$user)),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendNewOrderEmail(Order $order)
    {
        $subtotal = $order->getSubtotal();
        $gst      = $order->getGst();
        $pst      = $order->getPst();
        $total    = $order->getTotal();
        $shipping    = $this->shipping_rate;

        $message = \Swift_Message::newInstance()
            ->setSubject('New order from Purearth Organics')
            ->setFrom($this->email_sender)
            ->setTo($this->order_email_receiver)
            ->setBody(
                $this->twig->render('NSPurearthBundle:Email:new_order.html.twig', array('order'=>$order, 'subtotal'=>$subtotal, 'gst'=>$gst, 'pst'=>$pst, 'total'=>$total, 'shipping'=>$shipping)),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendPasswordReset(User $user, $token)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Your Purearth Organics login password has been reset')
            ->setFrom($this->email_sender)
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('NSPurearthBundle:Email:reset.html.twig', array('user'=>$user, 'token'=>$token)),
                'text/html'
            );

        $this->mailer->send($message);
    }
}