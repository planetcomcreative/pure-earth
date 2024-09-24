<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 27/04/18
 * Time: 4:40 PM
 */

namespace NS\PurearthBundle\Validator;


use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\User\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $existing = $this->entityManager->getRepository(User::class)->findOneBy(['email'=>$value]);

        if($existing)
        {
            $this->context->buildViolation($constraint->message)
                ->atPath('email')
                ->addViolation();
        }
    }
}
