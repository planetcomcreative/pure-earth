<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 13/03/19
 * Time: 3:42 PM
 */

namespace NS\Purearth\Stats\Service;


use Doctrine\ORM\EntityManager;
use NS\Purearth\Stats\Signup;

class SignupService
{
    protected $entityMgr;

    public function __construct(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function logSignup($type, $wasTriggered)
    {
        $signup = new Signup();
        $signup->setType($type);
        $signup->setWasTriggered($wasTriggered);

        $this->entityMgr->persist($signup);
        $this->entityMgr->flush($signup);
    }
}
