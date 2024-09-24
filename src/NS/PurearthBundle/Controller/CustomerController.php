<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\Order\Exceptions\OrderNotFoundException;
use NS\Purearth\User\Command\UpdateUserPasswordCommand;
use NS\Purearth\User\Events\UserEvent;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use NS\Purearth\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use NS\PurearthBundle\Form\UserProfileType;
use NS\PurearthBundle\Form\ResetPasswordType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/customer")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/", name="customer_dashboard")
     */
    public function indexAction(Request $request)
    {
        $newRegistration = $this->get('session')->get('newregistration', false);

        $user = $this->get('ns.purearth.user')->find($this->getUser()->getId());
        try
        {
            $orders = $this->get('ns.purearth.order')->findWithUser($user);
        }
        catch(OrderNotFoundException $e)
        {
            $orders = array();
        }

        return $this->render('NSPurearthBundle:Customer:index.html.twig', array('orders'=>$orders, 'user'=>$user, 'newRegistration'=>$newRegistration));
    }

    /**
     * @Route("/edit", name="customer_edit")
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('ns.purearth.user')->find($this->getUser()->getId());

        $subscriber = $this->get('ns.purearth.mailchimp')->quickGetSubscriber($user->getEmail());
        $isSubscribed = ($subscriber['status'] == 'subscribed');

        $user_data = $user->getProfileData();
        $user_data['forceResubscribe'] = $isSubscribed;

        $form = $this->createForm(UserProfileType::class,null,['user_data' => $user_data]);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $data = $form->getData();
            $this->get('command_bus')->handle($data);
            $this->get('ns_flash')->addSuccess(null, null, "Profile updated!");
            return $this->redirect($this->generateUrl('customer_dashboard'));
        }
        else if($form->isSubmitted())
        {
            $this->get('ns_flash')->addError(null, null, "Some required information was missing");
        }

        return $this->render('NSPurearthBundle:Customer:edit.html.twig', array('form'=>$form->createView(), 'isSubscribed'=>$isSubscribed));
    }

    /**
     * @Route("/changePassword", name="customer_change_password")
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $this->get('command_bus')->handle(new UpdateUserPasswordCommand($this->getUser()->getId(), $form->get('password')->getData()));
            $this->get('ns_flash')->addSuccess(null, null, "Password updated!");
            return $this->redirect($this->generateUrl('customer_dashboard'));
        }
        else if($form->isSubmitted())
        {
            $this->get('ns_flash')->addError(null, null, "Some required information was missing");
        }

        return $this->render('NSPurearthBundle:Customer:password.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/reset_password/{token}", name="resetPassword")
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $error = false;

        try
        {
            $user = $this->get('ns.purearth.user')->findByResetToken($token);
        }
        catch(UserNotFoundException $e)
        {
            $error['message'] = "This password reset link is no longer valid.";
        }

        $form->handleRequest($request);

        if($form->isValid())
        {
            $this->get('command_bus')->handle(new UpdateUserPasswordCommand($user->getId(), $form->get('password')->getData()));
            $this->get('ns_flash')->addSuccess(null, 'Success', "Password updated!");
            return $this->redirect($this->generateUrl('login'));
        }
        else if($form->isSubmitted())
        {
            $error['message'] = 'Your passwords did not match.';
        }

        return $this->render('NSPurearthBundle:Customer:reset_password.html.twig', ['form'=>$form->createView(), 'token'=>$token, 'error'=>$error]);
    }

    /**
     * @param Request $request
     * @Route("/subscribe", name="subscribe")
     */
    public function subscribeAction(Request $request)
    {
        if($this->getUser())
        {
            /** @var User $user */
            $user = $this->get('ns.purearth.user')->find($this->getUser()->getId());

            if ($request->isXmlHttpRequest())
            {
                $this->get('event_dispatcher')->dispatch(UserEvent::USER_RESUBSCRIBE, new UserEvent($user));

                return new Response('OK');
            }
        }

        return $this->render('NSPurearthBundle:Homepage:newsletter_sidebar.html.twig');
    }
}
