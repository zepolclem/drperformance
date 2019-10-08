<?php

namespace App\Form;

use App\Entity\Generation;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
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
            // ->add('endDate')
            ->add('resume')
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