<?php

namespace NS\PurearthBundle\Listeners;

use NS\Purearth\Common\TimestampableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimestampListener
{
    public function preUpdate(TimestampableInterface $object, LifecycleEventArgs $event)
    {
        $object->setUpdatedAt();
    }
}