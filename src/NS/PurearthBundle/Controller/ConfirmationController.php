<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 29/08/16
 * Time: 2:58 PM
 */

namespace NS\PurearthBundle\Controller;

use NS\Purearth\User\Command\ConfirmUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/confirmation")
 */
class ConfirmationController extends Controller
{
    /**
     * @Route("/unconfirmed", name="confirmation_unconfirmed")
     */
    public function unconfirmedAction(Request $request)
    {
        return $this->render('NSPurearthBundle:Confirmation:unconfirmed.html.twig');
    }

    /**
     * @Route("/confirm/{token}", name="confirmation_confirm")
     */
    public function confirmAction(Request $request, $token)
    {
        $command = new ConfirmUserCommand($token);

        $this->get('command_bus')->handle($command);

        return $this->render('NSPurearthBundle:Confirmation:confirm.html.twig');
    }

    /**
     * @Route("/resend", name="confirmation_resend")
     */
    public function resendConfirmationAction(Request $request)
    {
        $u = $this->getUser();
        if($u && $u->getId())
        {
            $user = $this->get('ns.purearth.user')->find($u->getId());

            $this->get('ns_purearth.mail')->sendWelcomeEmail($user);

            return $this->render('NSPurearthBundle:Confirmation:resent.html.twig');
        }
        else
        {
            $this->get('ns_flash')->addError(null, null, 'This user could not be found. Make sure you are logged in.');
            return $this->redirectToRoute('login');
        }
    }
}
