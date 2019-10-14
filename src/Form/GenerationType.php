<?php

namespace App\Form;

use App\Entity\Generation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GenerationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('startYear', Null, [
                'label' => 'De',
                // 'data' => '1984',
                'required' => true,
                'help' => "L'année de début de production.",
            ])
            ->add('endYear', Null, [
                'label' => 'à',
                // 'data' => '1984',
                'required' => false,
                'help' => "L'année de fin de production.",
            ])

            ->add('picture',  FileType::class, [
                'label' => 'Photo de la génération',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        // 'mimeTypes' => [
                        //     'image/png',
                        //     'image/jpeg',
                        // ],
                        // 'mimeTypesMessage' => 'Please upload a valid png, jpeg document',
                    ])
                ],
            ]);
            // ->add('endDate')
            // ->add('resume')
            // ->add('model')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Generation::class,
        ]);
    }
}