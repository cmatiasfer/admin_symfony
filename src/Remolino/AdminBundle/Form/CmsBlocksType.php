<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\CmsBlocks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,IntegerType,CheckboxType,HiddenType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CmsBlocksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cmsBlocks', EntityType::class, [
                'label' => 'Bloque superior',
                'class' => CmsBlocks::class,
                'choice_label' => 'name',
                'required' => false,
                'mapped' => false,
                'placeholder' => 'Elegí una bloque (opcional)'
            ])
            // "parent" permite guardar el item seleccionado
            // en "categories"
            ->add('parent', HiddenType::class, [
                'label' => 'Pertenece al bloque',
                'required' => false
            ])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('route', TextType::class, ['label' => 'Route' , 'required' => false, 'attr' => ['placeholder' => 'entity_index'], 'empty_data' => ''])
            ->add('iconClass', TextType::class, ['label' => 'Icon Class (https://fontawesome.com/v4.7.0/icons/)', 'attr' => ['placeholder' => 'fa fa-icon']])
            ->add('listingOrder', IntegerType::class, [
                'label' => 'Listing Order',
                'attr' => ['placeholder' => '100'],
                'required' => true
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsBlocks::class,
        ]);
    }
}
