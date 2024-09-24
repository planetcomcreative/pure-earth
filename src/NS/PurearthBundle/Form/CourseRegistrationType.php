<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/08/16
 * Time: 12:02 PM
 */

namespace NS\PurearthBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\ColorAdminBundle\Form\MaskedType;
use Symfony\Component\Validator\Constraints\NotBlank;

class CourseRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, ['required' => true, 'mapped'=>false, 'constraints'=>[new NotBlank()]]);
        $builder->add('address', null, ['required' => false, 'mapped'=>false]);
        $builder->add('postalCode', null, ['required' => false, 'mapped'=>false]);
        $builder->add('phone', MaskedType::class, ['mask'=>"(999) 999-9999", 'required'=>true, 'label'=>'Phone Number', 'mapped'=>false, 'constraints'=>[new NotBlank()]]);
//        $builder->add('registrantInfo');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\Purearth\Order\CourseRegistration',
        ]);
    }
}