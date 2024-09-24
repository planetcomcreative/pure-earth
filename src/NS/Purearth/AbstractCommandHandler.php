<?php

namespace NS\Purearth;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractCommandHandler
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * AbstractCommand constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $eventName
     * @param Event $event
     */
    public function dispatch($eventName, Event $event)
    {
        $this->dispatcher->dispatch($eventName, $event);
    }
}

