<?php

namespace NS\PurearthBundle\Admin;

use NS\ColorAdminBundle\Form\DatePickerType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CleansePercentSaleAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_cleanse_percent_sale';
    protected $baseRoutePattern = 'cleanse_percent_sale';

    protected $datagridValues = array (
        '_sort_by' => 'startDate',
        '_sort_order' => 'DESC'
    );
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('name')
            ->add('description')
            ->add('startDate')
            ->add('endDate')
            ->add('discount')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('startDate')
            ->add('endDate')
            ->add('discount', null, ['label'=>'Discount (%)'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('startDate', DatePickerType::class)
            ->add('endDate', DatePickerType::class)
            ->add('discount', NumberType::class, ['label'=>'Discount (%)'])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('description')
            ->add('startDate')
            ->add('endDate')
            ->add('discount')
            ->add('product')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
