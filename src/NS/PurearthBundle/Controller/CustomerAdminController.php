<?php

namespace NS\PurearthBundle\Controller;

use NS\Purearth\User\Command\GenerateUserResetTokenCommand;
use NS\Purearth\User\Command\PurgeUsersCommand;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomerAdminController extends CRUDController
{
    public function generatePasswordAction()
    {
        $object = $this->admin->getSubject();

        try
        {
            $command = new GenerateUserResetTokenCommand($object->getId());
            $this->get('command_bus')->handle($command);

            $this->get('ns_purearth.mail')->sendPasswordReset($object, $object->getResetToken());

            return new JsonResponse(['status' => 'success'], 200);
        }
        catch(\Exception $e)
        {
            return new JsonResponse(null, 410);
        }
    }

    public function purgeUsersAction()
    {
        $command = new PurgeUsersCommand();
        $this->get('command_bus')->handle($command);
        $this->get('ns_flash')->addSuccess('', 'Unconfirmed users cleared.');

        return $this->redirectToRoute('sonata_customer_list');
    }
}
