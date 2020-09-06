<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{IntegerType,TextType,TextareaType,CheckboxType};

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Fullname',
                'required' => true,
                'empty_data' => ''
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
                'empty_data' => ''
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('written', CheckboxType::class, [
                'label' => 'Written',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
