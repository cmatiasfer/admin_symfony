<?php

namespace App\Remolino\AdminBundle\Form\Searchbar;

use App\Remolino\CoreBundle\Entity\Texts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,TextareaType,ChoiceType};

class TextsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texto', TextType::class, [
                'label' => 'Texto',
                'attr' => [
                    'class' => 'col-sm-12 send_param'
                ]
            ])
        ;
    }
}
