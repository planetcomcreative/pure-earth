<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 27/04/18
 * Time: 5:01 PM
 */

namespace NS\PurearthBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUser extends Constraint
{
    public $message = 'This email address is already in use.';

    public function validatedBy()
    {
        return UniqueUserValidator::class;
    }
}
