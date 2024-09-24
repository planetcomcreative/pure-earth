<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 08/08/16
 * Time: 3:33 PM
 */

namespace NS\PurearthBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use NS\PurearthBundle\Form\CourseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('courses', CollectionType::class, array(
            'entry_type' => CourseType::class
        ));
    }
}