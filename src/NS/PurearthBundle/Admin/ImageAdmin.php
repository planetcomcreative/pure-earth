<?php

namespace NS\PurearthBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_image';
    protected $baseRoutePattern = 'image';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('title')
            ->add('description')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('thumbnail', null, array('template'=>'NSPurearthBundle:Admin:image_thumb.html.twig'))
            ->add('title')
            ->add('link', null, array('template'=>'NSPurearthBundle:Admin:image_link.html.twig'))
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
            ->add('title')
            ->add('description')
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
            ->add('createdAt')
            ->add('updatedAt')
            ->add('title')
            ->add('description')
            ->add('link', null, array('template'=>'NSPurearthBundle:Admin:image_link.html.twig'))
            ->add('thumbnail', null, array('template'=>'NSPurearthBundle:Admin:image_thumb.html.twig'))
        ;
    }
}
