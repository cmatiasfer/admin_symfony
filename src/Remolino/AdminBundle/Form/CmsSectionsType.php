<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\CmsSections;
use App\Remolino\CoreBundle\Entity\CmsBlocks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ChoiceType,IntegerType,CheckboxType,TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CmsSectionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('block', EntityType::class , [
                'class' => CmsBlocks::class,
                'choice_label' => 'name',
                'placeholder' => '-- Select Block --'

            ])
            ->add('route', TextType::class, ['label' => 'Route' , 'required' => false, 'attr' => ['placeholder' => 'entity_']])
            ->add('iconClass', TextType::class, ['label' => 'Icon Class'])
            ->add('description', TextType::class, ['label' => 'Description'])
            // ->add('primaryOrder', TextType::class, ['label' => 'Primary Order' , 'required' => false])
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
            'data_class' => CmsSections::class,
        ]);
    }
}
