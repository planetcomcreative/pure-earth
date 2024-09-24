<?php

namespace NS\PurearthBundle\Admin;

use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class OrderAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_order';
    protected $baseRoutePattern = 'order';

    protected $datagridValues = [
        'status' => ['value'=>OrderStatus::PAID],
        '_sort_by' => 'date',
        '_sort_order' => 'DESC'
    ];

//    public function createQuery($context = 'list')
//    {
//        $query = parent::createQuery($context);
//
//        if($context == 'show')
//        {
//            $query->addSelect('o')
//                ->leftJoin('o.OrderProducts', 'op')
//                ->leftJoin('op.product', 'p')
//                ->leftJoin('p.category', 'c')
//                ->leftJoin('o.CourseRegistrations', 'cr')
//                ->leftJoin('cr.course', 'c');
//
//            return $query;
//        }
//    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('date')
            ->add('status', 'doctrine_orm_choice', [], 'choice', ['choices'=>OrderStatus::getValuesForSelect()])
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('date')
            ->add('id', null, ['label'=>'Order #'])
            ->add('statusText', null, ['label'=>'Status'])
            ->add('payment.amount', null, ['label'=>'Total $'])
            ->add('delivery')
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('date')
            ->add('status')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
            ->add('date')
            ->add('statusText', null, ['label'=>'Status'])
            ->add('payment.amount', null, ['label'=>'Total $'])
            ->add('payment.chargeId', null, ['label'=>'Stripe Charge ID'])
            ->add('OrderProducts', null, ['template'=>'NSPurearthBundle:Order:Admin/order_products.html.twig'])
            ->add('delivery')
            ->add('deliveryAddress')
            ->add('comments')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('delete');
    }
}
