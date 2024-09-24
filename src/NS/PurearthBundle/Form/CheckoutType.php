<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 09/08/16
 * Time: 1:32 PM
 */

namespace NS\PurearthBundle\Form;


use Doctrine\Common\Collections\ArrayCollection;
use NS\Purearth\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\Purearth\Order\CourseRegistration;
use NS\PurearthBundle\Form\CourseRegistrationType;
use NS\Purearth\Product\Course;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $addr = array(
            'name'=> $user->getName(),
            'address'=> $user->getAddrStreet(),
            'postalCode' => $user->getAddrPostal(),
            'phone' => $user->getPrimaryPhone()
        );

        $builder->add('delivery', DeliveryType::class, ['data'=>$addr]);
        $builder->add('comments', TextareaType::class, ['required'=>false]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('user'=>null, 'order'=>null));
    }

    public function preSetData(FormEvent $event)
    {
        $form  = $event->getForm();
        $order = $form->getConfig()->getOption('order');

        $hasCourse = false;
        $courses = new ArrayCollection();

        if($order)
        {
            foreach ($order->getOrderProducts() as $oproduct)
            {
                if ($oproduct->getProduct() instanceof Course)
                {
                    $course = $oproduct->getProduct();
                    $hasCourse = true;
                    $courses->add($course);

                    for ($i = 0; $i < $oproduct->getQuantity(); $i++)
                    {
                        $course->addNewRegistration(new CourseRegistration($oproduct->getProduct()));
                    }
                }
            }
        }

        if($hasCourse)
        {
            $form->add('registrations', CourseCollectionType::class, ['data'=>['courses'=>$courses]]);
        }
    }
}