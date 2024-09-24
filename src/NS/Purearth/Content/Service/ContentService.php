<?php

namespace NS\Purearth\Content\Service;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use NS\Purearth\Content\Exceptions\ContentNotFoundException;
use NS\Purearth\Content\Exceptions\ContentNotUniqueException;

class ContentService
{
    protected $entityMgr;

    public function __construct($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function get($slug)
    {
        try
        {
            $content = $this->entityMgr->getRepository('NS\Purearth\Content\Content')->findOneBy(['slug' => $slug]);

            if(!$content)
            {
                throw new NoResultException();
            }
        }
        catch(NoResultException $e)
        {
            throw new ContentNotFoundException();
        }
        catch(NonUniqueResultException $e)
        {
            throw new ContentNotUniqueException();
        }

        return $content;
    }
}