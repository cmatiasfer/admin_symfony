<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\CmsTexts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CmsTextsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variable', TextType::class, ['label' => 'Variable'])
            ->add('title_EN', TextType::class, [
                'label' => 'Title',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('title_ES', TextType::class, [
                'label' => 'Título',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('text_EN', TextareaType::class, [
                'label' => 'Text',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('text_ES', TextareaType::class, [
                'label' => 'Texto',
                'required' => false,
                'empty_data' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsTexts::class,
        ]);
    }
}
