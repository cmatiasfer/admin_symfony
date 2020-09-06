<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\ImagesSite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, TextareaType, ChoiceType, HiddenType, FileType};
use Presta\ImageBundle\Form\Type\ImageType;

class ImagesSiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre'
            ])
            ->add('description', TextType::class, [
                'label' => 'Descripción'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Imagen Principal',
                'help' => 'Los archivos gif animados no esta permitidos.',
                'required' => false,
            ])
            ->add('image', HiddenType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'preview-image',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImagesSite::class,
        ]);
    }
}
