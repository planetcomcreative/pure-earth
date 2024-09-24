<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 06/05/16
 * Time: 3:55 PM.
 */
namespace NS\PurearthBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use NS\Purearth\User\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoderProcessor implements ProcessorInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $factory;

    private $encoder;

    /**
     * PasswordEncoderProcessor constructor.
     *
     * @param EncoderFactoryInterface $factory
     */
    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function preProcess($user)
    {
        if ($user instanceof User) {
            if (!$this->encoder) {
                $this->encoder = $this->factory->getEncoder($user);
            }

            $user->setPassword($this->encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
        }
    }

    public function postProcess($object)
    {
    }
}

