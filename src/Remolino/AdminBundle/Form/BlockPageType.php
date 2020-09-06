<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\BlockPage;
use App\Remolino\CoreBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,TextareaType,CheckboxType,HiddenType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BlockPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('page', EntityType::class, [
            'class' => Page::class,
            'choice_label' => 'title',
            'placeholder' => 'Choose a page'
        ])
        ->add('title', TextType::class, [
            'label' => 'Title',
            'required' => true
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'empty_data' => ''
        ])
        ->add('route', TextType::class, [
            'label' => 'Route',
            'required' => false,
            'empty_data' => ''
        ])
        ->add('seoURL', TextType::class, [
            'label' => 'SEO URL',
            'required' => true,
            'empty_data' => ''
        ])
        ->add('seoTITLE', TextType::class, [
            'label' => 'SEO TITLE',
            'required' => false,
            'empty_data' => ''
        ])
        ->add('seoDESC', TextareaType::class, [
            'label' => 'SEO DESC',
            'required' => false,
            'empty_data' => ''
        ])
        ->add('listingOrder', TextType::class, [
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
            'data_class' => BlockPage::class,
        ]);
    }
}