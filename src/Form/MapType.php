<?php

namespace App\Form;

use App\Entity\Map;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'help' => 'Ex : Stage 1'
            ])
            ->add('power', null, [
                'label' => 'Puissance',
                'help' => 'En en cv'
            ])
            ->add('torque', null, [
                'label' => 'Couple',
                'help' => 'En Nm'
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Résumé',
                'attr' => ['class' => 'ckeditor']
            ])
            ->add('price', null, [
                'label' => 'Prix',
                'help' => ''
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Map::class,
        ]);
    }
}