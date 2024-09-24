<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 29/08/16
 * Time: 4:31 PM
 */

namespace NS\PurearthBundle\Listeners;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class UserUnconfirmedListener implements AccessDeniedHandlerInterface
{
    protected $security;
    protected $router;

    public function __construct(AuthorizationCheckerInterface $security, Router $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if($this->security->isGranted('ROLE_CUSTOMER') && !$this->security->isGranted('ROLE_CONFIRMED_USER'))
        {
            return new RedirectResponse($this->router->generate('confirmation_unconfirmed'));
        }
    }
}