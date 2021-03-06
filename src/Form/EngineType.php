<?php

namespace App\Form;

use App\Entity\Engine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EngineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'help' => 'Ex : 1.9 TDi'
            ])
            ->add('power', null, [
                'label' => 'Puissance',
                'help' => 'En ch'
            ])
            ->add('torque', null, [
                'label' => 'Couple',
                'help' => 'En Nm'
            ])
            ->add('energy', null, [
                'label' => 'Énergie',
            ])
            ->add('cylinderCapacity', null, [
                'label' => 'Cylindrée',
                'help' => 'En cm3'
            ])
            ->add('turbo');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Engine::class,
        ]);
    }
}