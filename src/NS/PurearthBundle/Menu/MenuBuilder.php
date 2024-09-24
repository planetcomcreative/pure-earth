<?php

namespace NS\PurearthBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createFrontendMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Store', array('route'=>'store'));
        $menu->addChild('Specials', array('route'=>'specials_list'));
        $menu->addChild('Juices', array('route'=>'juices'));
        $menu->addChild('Cleanses', array('route'=>'juice_list'));
        $menu->addChild('Classes', array('route'=>'course_list'));
//        $menu->addChild('Classes', array('uri'=>'#'));
        $menu->addChild('Blog', array('route'=>'wp_index'));
        $menu->addChild('My Purearth', array('route'=>'customer_dashboard'));

        return $menu;
    }

    public function createFrontendTopMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('About Us', array('route'=>'about_us'));
        $menu->addChild('Contact Us', array('route'=>'contact_us'));
        $menu->addChild('FAQs', array('route'=>'faqs'));
        $menu->addChild('Links', array('route'=>'links'));

        return $menu;
    }

    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Classes', array('route'=>'sonata_class_list'))
            ->setAttribute('icon', 'graduation-cap');

        $menu->addChild('Class Registrations', array('route'=>'sonata_class_registration_list'))
            ->setAttribute('icon', 'user-plus');

        $menu->addChild('Cleanses', array('route'=>'sonata_juice_list'))
            ->setAttribute('icon', 'tint');

        $menu->addChild('Cleanse Orders', array('route'=>'sonata_cleanse_order_list'))
            ->setAttribute('icon', 'glass')
            ->setAttribute('icon-style', 'primary');

        $menu->addChild('All Orders', array('route'=>'sonata_order_list'))
            ->setAttribute('icon', 'usd')
            ->setAttribute('icon-style', 'success');

        $menu->addChild('Sales', array('route'=>'sonata_cleanse_percent_sale_list'))
            ->setAttribute('icon', 'tags')
            ->setAttribute('icon-style', 'warning');

        $menu->addChild('Specials', array('route'=>'sonata_special_list'))
            ->setAttribute('icon', 'star');

        $menu->addChild('Categories', array('route'=>'sonata_product_category_list'))
            ->setAttribute('icon', 'th-list');

        $menu->addChild('Customers', array('route'=>'sonata_customer_list'))
            ->setAttribute('icon', 'users');

        return $menu;
    }

    public function createWebsiteMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Content', array('route'=>'sonata_content_list'))
            ->setAttribute('icon', 'list');

        $menu->addChild('Images', array('route'=>'sonata_image_list'))
            ->setAttribute('icon', 'picture-o');

        $menu->addChild('Administrators', array('route'=>'sonata_administrator_list'))
            ->setAttribute('icon', 'users');

        $menu->addChild('Config', array('route'=>'sonata_config_list'))
            ->setAttribute('icon', 'gears');

        return $menu;
    }
}
