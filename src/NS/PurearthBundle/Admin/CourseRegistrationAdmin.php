<?php

namespace NS\PurearthBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use NS\Purearth\Order\OrderStatus;
use NS\Purearth\Product\Course;
use NS\Purearth\User\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CourseRegistrationAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_class_registration';
    protected $baseRoutePattern = 'class_registration';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->leftJoin($query->getRootAlias().'.order', 'ord')
                ->andWhere('ord.status = :order_status')
                ->setParameter('order_status', OrderStatus::PAID);
        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $courses = $this->modelManager->createQuery(Course::class, 'c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();

        $datagridMapper
            ->add('createdAt')
            ->add('updatedAt')
            ->add('registrantInfo')
            ->add('course', null, [
                'field_options' => [
                    'choices' => $courses
                ]
            ])
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt')
            ->add('registrantInfo')
            ->add('course')
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
            ->add('user', null, [], ['admin_code'=>'ns_purearth.admin.customer'])
            ->add('registrantInfo')
            ->add('course', null, [
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('registrantInfo')
            ->add('course')
            ->add('user', null, ['admin_code'=>'ns_purearth.admin.customer'])
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
