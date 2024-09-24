<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 06/06/16
 * Time: 12:48 PM.
 */
namespace NS\PurearthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'New Password'],
            'second_options' => ['label' => 'Confirm New Password'],
        ]);
    }
}
