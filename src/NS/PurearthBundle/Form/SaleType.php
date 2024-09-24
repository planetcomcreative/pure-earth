<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 21/09/17
 * Time: 11:42 AM
 */

namespace NS\PurearthBundle\Form;


use NS\Purearth\Product\Sale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('price')
                ->add('salePrice')
                ->add('unit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>Sale::class]);
    }
}