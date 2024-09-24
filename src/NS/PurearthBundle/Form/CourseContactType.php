<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 24/08/16
 * Time: 12:13 PM
 */

namespace NS\PurearthBundle\Form;


use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class CourseContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, ['required'=>true, 'attr'=>['placeholder'=>'What class would you like to see?']])
                ->add('email', EmailType::class, ['required'=>true, 'attr'=>['placeholder'=>'Your email address']])
                ->add('recaptcha', EWZRecaptchaType::class, [
                    'attr'=>[
                        'options'=>[
                            'theme' => 'light',
                            'type'  => 'image',
                            'size' => 'invisible',
                            'defer' => true,
                            'async' => true,
//                            'callback' => 'onReCaptchaSuccess', // callback will be set by default if not defined (along with JS function that validate the form on success)
                            'bind' => 'contact_submit'             // this is the id of the form submit button,
                        ]
                    ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\PurearthBundle\Entity\CourseContact',
        ]);
    }
}
