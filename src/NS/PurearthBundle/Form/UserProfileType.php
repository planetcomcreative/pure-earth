<?php

namespace NS\PurearthBundle\Form;

use NS\ColorAdminBundle\Form\MaskedType;
use NS\Purearth\User\Command\UpdateUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserProfileType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName', null, array('required'=>true))
                ->add('firstName', null, array('required'=>true))
                ->add('email', EmailType::class, array('required'=>true, 'attr'=>array('class'=>'form-control')))
                ->add('addrStreet', null, array('required'=>false, 'label'=>'Address'))
                ->add('addrCity', null, array('required'=>false, 'label'=>'City'))
//                ->add('addrProv', null, array('required'=>false, 'label'=>'Province'))
                ->add('addrPostal', null, array('required'=>false, 'label'=>'Postal Code'))
                ->add('primaryPhone', MaskedType::class, array('mask'=>"(999) 999-9999", 'required'=>true, 'label'=>'Phone Number'))
                ->add('forceResubscribe', CheckboxType::class, ['required'=>false, 'label'=>'Subscribe to our newsletter']);
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\Purearth\User\Command\UpdateUserCommand',
            'empty_data' => null
        ));
        $resolver->setRequired('user_data');
    }

    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);
        /** @var FormConfigInterface $config */
        $config = $forms['lastName']->getParent()->getConfig();
        $userData = $config->getOption('user_data');
        foreach($forms as $field=>$form){
            if(isset($userData[$field])) {
                $form->setData($userData[$field]);
            }
        }
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        /** @var FormConfigInterface $config */
        $config = $forms['lastName']->getParent()->getConfig();
        $userData = $config->getOption('user_data');
        $data = new UpdateUserCommand($userData['id'], $forms['lastName']->getData(), $forms['firstName']->getData(),$forms['email']->getData(),  $forms['addrStreet']->getData(),  $forms['addrCity']->getData(), null, $forms['addrPostal']->getData(),  $forms['primaryPhone']->getData(), null, $forms['forceResubscribe']->getData());
    }
}
