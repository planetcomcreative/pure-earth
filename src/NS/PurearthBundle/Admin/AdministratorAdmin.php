<?php

namespace NS\PurearthBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\EqualType;

class AdministratorAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_administrator';
    protected $baseRoutePattern = 'administrator';

    protected $datagridValues = array (
        '_sort_by' => 'date',
        '_sort_order' => 'DESC'
    );

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere($query->getRootAlias().'.admin = :admin')
            ->setParameter('admin', true);
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
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('registeredOn')
            ->add('lastLogin')
            ->add('addrStreet')
            ->add('addrCity')
            ->add('addrPostal')
            ->add('primaryPhone')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('primaryPhone')
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
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('addrStreet')
            ->add('addrCity')
            ->add('addrPostal')
            ->add('primaryPhone')
            ->add('admin')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('lastName')
            ->add('firstName')
            ->add('email')
            ->add('registeredOn')
            ->add('lastLogin')
            ->add('addrStreet')
            ->add('addrCity')
            ->add('addrPostal')
            ->add('primaryPhone')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
