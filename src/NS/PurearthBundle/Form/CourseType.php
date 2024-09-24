<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/08/16
 * Time: 1:50 PM
 */

namespace NS\PurearthBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('newRegistrations', CollectionType::class, [
            'entry_type' => CourseRegistrationType::class,
            'by_reference'=>false,
        ]);
        $builder->add('id', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\Purearth\Product\Course',
        ]);
    }
}