<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\Corebundle\Entity\CmsUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType, CheckboxType, RepeatedType, PasswordType, HiddenType};

class CmsUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => ['class' => 'form-group form-control']
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => array(
                    'user' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN',
                ),
                'multiple' => true
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password']
            ))
            ->add('status', CheckboxType::class, [
                'label' => 'Active',
                'required' => false
            ])
            ->add('createdAt', HiddenType::class, [
                'empty_data' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsUser::class,
        ]);
    }
}
