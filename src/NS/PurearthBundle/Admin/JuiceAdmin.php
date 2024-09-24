<?php

namespace NS\PurearthBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Sonata\AdminBundle\Route\RouteCollection;

class JuiceAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_juice';
    protected $baseRoutePattern = 'juice';

    public $last_position = 0;
    private $positionService;

//    protected $datagridValues = array(
//        '_page' => 1,
//        '_sort_order' => 'ASC',
//        '_sort_by' => 'position',
//    );

//    public function setPositionService(\Pix\SortableBehaviorBundle\Services\PositionHandler $positionHandler)
//    {
//        $this->positionService = $positionHandler;
//    }
//
//    protected function configureRoutes(RouteCollection $collection)
//    {
//        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
//    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('gst')
            ->add('pst')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
//        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

        $listMapper
            ->add('name')
            ->add('productCategory', null, ['label'=>'Category'])
            ->add('price')
            ->add('gst')
            ->add('pst')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
//                    'move' => array('template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig')
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
            ->add('productCategory', null, ['label'=>'Category'])
            ->add('price', null, array('required'=>true))
            ->add('summary', TextareaType::class, array('required'=>true))
            ->add('description', CKEditorType::class, array('required'=>true))
            ->add('gst')
            ->add('pst')
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
            ->add('price')
            ->add('description')
            ->add('gst')
            ->add('pst')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
