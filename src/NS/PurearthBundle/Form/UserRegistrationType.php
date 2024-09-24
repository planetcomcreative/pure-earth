<?php

namespace NS\PurearthBundle\Form;

use NS\Purearth\User\Command\RegisterUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use NS\ColorAdminBundle\Form\MaskedType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName', null, array('required'=>true))
            ->add('firstName', null, array('required'=>true))
            ->add('addrStreet', null, array('required'=>false, 'label'=>'Address'))
            ->add('addrCity', null, array('required'=>false, 'label'=>'City'))
            ->add('addrPostal', null, array('required'=>false, 'label'=>'Postal Code'))
            ->add('primaryPhone', MaskedType::class, array('mask'=>"(999) 999-9999", 'required'=>true, 'label'=>'Phone Number'))
            ->add('email', EmailType::class, array('required'=>true, 'attr'=>array('class'=>'form-control')))
            ->add('password', RepeatedType::class, array(
                'label' => 'Password',
                'required' => true,
                'type' => PasswordType::class, 'first_options' => array('label' => 'Password'), 'second_options' => array('label' => 'Re-Enter Password') ))
            ->add('recaptcha', EWZRecaptchaType::class, [
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue()
                ],
                'attr'=>[
                    'options'=>[
                        'theme' => 'light',
                        'type'  => 'image',
                        'size' => 'invisible',
                        'defer' => true,
                        'async' => true,
//                            'callback' => 'onReCaptchaSuccess', // callback will be set by default if not defined (along with JS function that validate the form on success)
                        'bind' => 'register_submit'             // this is the id of the form submit button,
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\Purearth\User\Command\RegisterUserCommand',
            'empty_data' => function (FormInterface $form) {
                return new RegisterUserCommand(
                    $form->get('lastName')->getData(),
                    $form->get('firstName')->getData(),
                    $form->get('email')->getData(),
                    $form->get('password')->getData(),
                    $form->get('addrStreet')->getData(),
                    $form->get('addrCity')->getData(),
                    $form->get('addrPostal')->getData(),
                    $form->get('primaryPhone')->getData()
                );
            },
        ));
    }
}
