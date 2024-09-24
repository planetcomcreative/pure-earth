<?php

namespace NS\PurearthBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\EqualType;

class CustomerAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_customer';
    protected $baseRoutePattern = 'customer';

    protected $datagridValues = array (
        '_sort_by' => 'date',
        '_sort_order' => 'DESC'
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('generatePassword', $this->getRouterIdParameter().'/generate_password');
        $collection->add('purgeUsers', $this->getRouterIdParameter().'/purge_users');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere($query->getRootAlias().'.admin = :admin')
            ->setParameter('admin', false);
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
            ->add('confirmed')
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
            ->add('confirmed')
            ->add('_action', null, array(
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'generatePassword' => [
                        'template' => 'NSPurearthBundle:CRUD:list__action_generate_password.html.twig'
                    ]
//                    'delete' => array(),
                ]
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
            ->add('confirmed')
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
            ->add('confirmed')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

//    public function getDashboardActions()
//    {
//        $actions = parent::getDashboardActions();
//
//        $actions['purge'] = array(
//            'label' => 'link_purge',
//            'translation_domain' => 'SonataAdminBundle',
//            'template' => 'PurearthBundle:Admin:User/purege.html.twig',
//            'url' => 'http://www.google.com',//$this->generateUrl('create'),
//            'icon' => 'plus-circle',
//        );
//
//        return $actions;
//    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if (in_array($action, array('tree', 'show', 'edit', 'delete', 'list', 'batch'))
            && $this->hasAccess('create')
            && $this->hasRoute('create')
        ) {
            $list['purge'] = array(
                'template' => 'NSPurearthBundle:Admin:User/purge.html.twig'
            );
        }

        return $list;
    }
}
