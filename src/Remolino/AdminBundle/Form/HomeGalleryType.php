<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\Home;
use App\Remolino\CoreBundle\Entity\HomeGallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,TextareaType,CheckboxType,HiddenType, ChoiceType};
use Presta\ImageBundle\Form\Type\ImageType;

class HomeGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('colorTitle', ChoiceType::class, [
            //     'label' => 'Color Title',
            //     'choices'  => [
            //         '-- --' => null,
            //         'Red' => 'red',
            //         'White' => 'white',
            //     ],
            // ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Text',
                'required' => false
            ])
            ->add('listingOrder', TextType::class, [
                'label' => 'Listing Order',
                'required' => false
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false
            ])
            // ->add('seoURL', ChoiceType::class, [
            //     'label' => 'SEO URL',
            //     'help' => "If you do not want to show a button, choose '----'",
            //     'choices'  => [
            //         '----' => '',
            //         'Beneficios' => 'benefit_web',
            //         'Comercios' => 'commerce_web',
            //         'Home' => 'home_web',
            //         'Personas' => 'people_web',
            //     ],
            //     'required' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeGallery::class,
        ]);
    }
}
