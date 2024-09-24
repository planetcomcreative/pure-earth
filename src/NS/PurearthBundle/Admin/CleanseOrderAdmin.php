<?php

namespace NS\PurearthBundle\Admin;

use NS\Purearth\Order\Order;
use NS\Purearth\Order\OrderStatus;
use NS\Purearth\Product\Juice;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CleanseOrderAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_cleanse_order';
    protected $baseRoutePattern = 'cleanse_order';

    protected $datagridValues = array (
        'status' => array('value'=>OrderStatus::PAID),
        '_sort_by' => 'date',
        '_sort_order' => 'DESC'
    );

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->leftJoin($query->getRootAliases()[0].'.orderProducts', 'op')
            ->leftJoin('op.product', 'p')
            ->andWhere('p INSTANCE OF :type')
            ->setParameter('type', 'juice');

        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('date')
            ->add('status', 'doctrine_orm_choice', array(), 'choice', array('choices'=>OrderStatus::getValuesForSelect()))
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
            ->add('statusText', null, array('label'=>'Status'))
            ->add('payment.amount', null, array('label'=>'Total $'))
            ->add('delivery')
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
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
            ->add('statusText', null, array('label'=>'Status'))
            ->add('payment.amount', null, array('label'=>'Total $'))
            ->add('payment.chargeId', null, array('label'=>'Stripe Charge ID'))
            ->add('OrderProducts', null, array('template'=>'NSPurearthBundle:Order:Admin/order_products.html.twig'))
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
