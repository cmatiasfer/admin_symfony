<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\MailTemplate;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{IntegerType, CheckboxType, TextType, HiddenType , TextareaType,ChoiceType};

class MailTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameTemplate', TextType::class, [
                'label' => 'Nombre de la Plantilla',
                'required' => true,
                'empty_data' => ''
            ])
            ->add('subject', TextType::class, [
                'label' => 'Asunto',
                'required' => true,
                'empty_data' => ''
            ])
            ->add('typeSend', ChoiceType::class, [
                'label' => 'Tipo de envio',
                'choices'  => [
                    '-- --' => null,
                    'Admin a Cliente' => 1,
                    'Cliente a Admin' => 2,
                ],
            ])
            ->add('mailTo', TextType::class, [
                'label' => 'Email',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('html', TextareaType::class, [
                'label' => 'Html',
                'required' => true,
                'empty_data' => ''
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
            'data_class' => MailTemplate::class,
        ]);
    }
}
