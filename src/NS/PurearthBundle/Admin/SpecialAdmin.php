<?php

namespace NS\PurearthBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use NS\PurearthBundle\Form\SaleType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SpecialAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_special';
    protected $baseRoutePattern = 'special';

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
            ->add('name')
            ->add('description')
            ->add('startDate')
            ->add('endDate')
            ->add('createdAt')
            ->add('updatedAt')
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
            ->add('name', null, array('required'=>true))
            ->add('sales', 'sonata_type_native_collection', [
                'entry_type'=>SaleType::class,
                'prototype'=>true,
                'allow_add' =>true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false
            ])
            ->add('description', CKEditorType::class, array('required'=>true))
            ->add('gst')
            ->add('pst')
            ->add('startDate', DatePickerType::class, array(
                    'dp_use_current'        => true,
                    'required'=>true
                ))
            ->add('endDate', DatePickerType::class, array(
                'dp_use_current'        => true,
                'required'=>true
            ))
            ->add('imageFile', VichImageType::class, array(
                'label' => 'Image',
                'required' => false,
                'allow_delete' => true,
                'download_link' => true
            ))
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
            ->add('gst')
            ->add('pst')
            ->add('startDate')
            ->add('endDate')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
