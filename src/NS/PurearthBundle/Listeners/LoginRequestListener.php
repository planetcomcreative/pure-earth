<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/06/16
 * Time: 2:21 PM.
 */
namespace NS\PurearthBundle\Listeners;

use NS\FlashBundle\Model\FlashMessage;
use NS\FlashBundle\Service\Messages;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginRequestListener
{
    /**
     * @var Security
     */
    protected $security;
    /**
     * @var Messages
     */
    protected $messages;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * LoginRequestListener constructor.
     *
     * @param AuthorizationCheckerInterface $authChecker
     * @param RouterInterface               $router
     */
    public function __construct(AuthorizationCheckerInterface $authChecker, RouterInterface $router, EventDispatcherInterface $dispatcher, Security $security, Messages $messages)
    {
        $this->authChecker = $authChecker;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->security = $security;
        $this->messages = $messages;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->authChecker->isGranted('ROLE_SUPER_ADMIN'))
            {
                $event->setResponse(new RedirectResponse($this->router->generate('admin_dashboard')));
            }
            else
            {
                if(!$this->security->getUser()->getPhone())
                {
                    $this->messages->addWarning('', 'Some required profile information is missing', 'Please update your phone number');
                    $event->setResponse(new RedirectResponse($this->router->generate('customer_edit')));
                }
                else
                {
                    $event->setResponse(new RedirectResponse($this->router->generate('homepage')));
                }
            }
        }
    }
}

