<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 04/08/16
 * Time: 12:31 PM
 */

namespace NS\PurearthBundle\Form;


use NS\Purearth\User\User;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\ColorAdminBundle\Form\MaskedType;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('delivery', ChoiceType::class, ['required'=>false, 'choices'=>[0, 1]]);
        $builder->add('name', null, array('required' => false));
        $builder->add('address', null, array('required' => false));
        $builder->add('postalCode', null, array('required' => false));
        $builder->add('phone', MaskedType::class, array('mask'=>"(999) 999-9999", 'required'=>false, 'label'=>'Phone Number'));
    }

}