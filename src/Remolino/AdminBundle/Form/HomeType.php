<?php

namespace App\Remolino\AdminBundle\Form;

use App\Remolino\CoreBundle\Entity\Home;
use App\Remolino\CoreBundle\Entity\HomeGallery;
use App\Remolino\CoreBundle\Service\AdminService;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Presta\ImageBundle\Form\Type\ImageType;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'empty_data' => ''
            ])
            ->add('gallery', HiddenType::class, [
                'label' => 'Galería',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'dropzoneGallery',
                    'data-addform' => $options["configDropzone"]['ADD_FORM'],
                    'data-filetype' => $options["configDropzone"]['FILETYPE'],
                    'data-lang' => $options["configDropzone"]['LANG'],
                    'data-maxfilesize' => $options["configDropzone"]['MAXFILESIZE'],
                    'data-rules' => json_encode($options["configDropzone"]['rules_image']),
                    /* 'data-modeeditionimage' => $options["configDropzone"]['MODE_EDITION_IMAGE'],
                    'data-dimensionimage' => $options["configDropzone"]['DIMENSIONS'], */
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Home::class,
        ])
        ->setRequired(array(
            'configDropzone'
        ));
    }
}
