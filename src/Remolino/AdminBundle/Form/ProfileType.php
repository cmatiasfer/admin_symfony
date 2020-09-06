<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\Profile;
use App\Remolino\CoreBundle\Entity\CmsUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, HiddenType, IntegerType};
use Presta\ImageBundle\Form\Type\ImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', ImageType::class, [
                'label' => 'Profile Image',
                'help' => 'Dimensions: min. 256x256px / Formats: .jpg, .png, .jpeg / Max. 2048kB',
                'required' => false,
                'upload_button_class' => 'btn btn-sm btn-default',
                'preview_height' => 'auto',
                'max_width' => 256,
                'max_height' => 256,
                'enable_remote' => false,
                'upload_mimetype' => 'image/jpeg',
                'aspect_ratios' => [] // Needs to be empty
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'required' => false,    
                'empty_data' => ''
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'empty_data' => ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
