<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\User\Command\GenerateUserResetTokenCommand;
use NS\Purearth\User\Command\UpdateUserPasswordCommand;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use NS\PurearthBundle\Util\Password;
use NS\PurearthBundle\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login",name="login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('NSPurearthBundle:Login:login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/register",name="register")
     */
    public function registerAction(Request $request)
    {
        if($this->isGranted('ROLE_CUSTOMER'))
        {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserRegistrationType::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->get('command_bus')->handle($form->getData());
            $this->get('session')->set('newregistration', true);
            $this->get('ns_flash')->addSuccess(null, 'Welcome!', 'Your registration was successful! You will receive a confirmation email shortly - please follow the link in the email to confirm your registration. You need to confirm registration before you will be able to place an order.');

            return $this->redirectToRoute('customer_dashboard');
        }

        return $this->render('NSPurearthBundle:Login:register.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/forgotPassword",name="forgotPassword")
     */
    public function forgotPasswordAction(Request $request)
    {
        if ($request->getMethod() === 'POST')
        {
            try
            {
                $username = $request->get('_username');
                $user = $this->get('ns.purearth.user')->findByUsername($username);

                $command = new GenerateUserResetTokenCommand($user->getId());
                $this->get('command_bus')->handle($command);

                $this->get('ns_purearth.mail')->sendPasswordReset($user, $user->getResetToken());
            }
            catch(UserNotFoundException $e)
            {
                $request->getSession()->getFlashBag()->add('userNotFound','User Not Found');
                return $this->redirectToRoute('forgotPassword');
            }

            return $this->redirectToRoute('forgot_success');
        }

        return $this->render('NSPurearthBundle:Login:forgot.html.twig');
    }

    /**
     * @Route("/forgotSuccess",name="forgot_success")
     */
    public function forgotSuccessAction(Request $request)
    {
        return $this->render('NSPurearthBundle:Login:forgotSuccess.html.twig');
    }
}
