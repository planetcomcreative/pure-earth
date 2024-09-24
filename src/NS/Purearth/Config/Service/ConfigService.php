<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 09/02/17
 * Time: 11:20 AM
 */

namespace NS\Purearth\Config\Service;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use NS\Purearth\Config\Exceptions\ConfigNotFoundException;
use NS\Purearth\Config\Exceptions\ConfigNotUniqueException;

class ConfigService
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
            $config = $this->entityMgr->getRepository('NS\Purearth\Config\Config')->findOneBy(['slug' => $slug]);

            if(!$config)
            {
                throw new NoResultException();
            }
        }
        catch(NoResultException $e)
        {
            throw new ConfigNotFoundException();
        }
        catch(NonUniqueResultException $e)
        {
            throw new ConfigNotUniqueException();
        }

        return $config;
    }
}