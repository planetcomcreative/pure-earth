<?php

namespace NS\PurearthBundle\Listeners;

use NS\Purearth\Content\Content;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ContentListener
{
    public function prePersist(Content $object, LifecycleEventArgs $event)
    {
        $object->setSlug();
    }
}