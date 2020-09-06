<?php

namespace App\Remolino\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,TextareaType,ChoiceType,CheckboxType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdminSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      if( (array_key_exists("keyword", $options['data'])) ) {
        $builder->add('keyword', TextType::class, [
          'label' => 'Keyword',
          'attr' => [
              'class' => 'col-sm-12'
          ],
          'required' => false,
        ]);  
      }
      
      if( (array_key_exists("category", $options['data'])) ) {
        $builder->add('category', EntityType::class, [
          'class' => "App\Remolino\CoreBundle\Entity\\" . $options['configForm']['category']['className'],
          'choice_label' => 'nameEN',
          'label' => 'Category',
          'required' => false,
          'empty_data' => '',
          'placeholder' => 'Choose a category'
        ]);
      }
      
      if( (array_key_exists("visible", $options['data'])) ) {
        $builder->add('visible', CheckboxType::class, [
          'label' => 'Visible',
          'required' => false
        ]);
      }
      
      if( (array_key_exists("inHome", $options['data'])) ) {
        $builder->add('inHome', CheckboxType::class, [
          'label' => 'in Home',
          'required' => false
        ]);
      }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'configForm'
        ));
    }
}