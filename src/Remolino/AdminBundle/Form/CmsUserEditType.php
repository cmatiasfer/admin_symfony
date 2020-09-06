<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\Corebundle\Entity\CmsUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType, CheckboxType};

class CmsUserEditType extends AbstractType
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
            ->add('status', CheckboxType::class, [
                'label' => 'Active',
                'required' => false
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
