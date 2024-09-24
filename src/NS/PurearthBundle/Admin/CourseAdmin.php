<?php

namespace NS\PurearthBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_class';
    protected $baseRoutePattern = 'class';

    protected $datagridValues = array (
        '_sort_by' => 'date',
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
            ->add('subtitle')
            ->add('price')
            ->add('description')
            ->add('gst')
            ->add('pst')
            ->add('date')
            ->add('startTime')
            ->add('endTime')
            ->add('maxSeats')
            ->add('registrationCutoff')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('price', null, array('required'=>false))
            ->add('date')
            ->add('startTime')
            ->add('endTime')
            ->add('maxSeats', null, array('required'=>false))
            ->add('registrationCutoff')
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
            ->add('subtitle', null, array('required'=>false))
            ->add('price')
            ->add('summary', CKEditorType::class)
            ->add('description', CKEditorType::class)
            ->add('gst')
            ->add('pst')
            ->add('date', DatePickerType::class, array(
                'dp_use_current'        => true,
            ))
            ->add('startTime', TimeType::class, array(
                'input_wrapper_class' => 'col-sm-3'
            ))
            ->add('endTime', TimeType::class, array(
                'input_wrapper_class' => 'col-sm-3'
            ))
            ->add('maxSeats')
            ->add('registrationCutoff', DatePickerType::class, array(
                'dp_use_current'        => true,
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
            ->add('subtitle')
            ->add('price')
            ->add('summary')
            ->add('description')
            ->add('gst')
            ->add('pst')
            ->add('date')
            ->add('startTime')
            ->add('endTime')
            ->add('maxSeats')
            ->add('registrationCutoff')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
