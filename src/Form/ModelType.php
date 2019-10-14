<?php

namespace App\Form;

use App\Entity\Model;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            // ->add('resume')
            ->add('manufacturer', null, [
                'label' => 'Constructeur',
                'choice_label' => 'name',
                'choices' => $options['manufacturers'],
                'data' => $options['manufacturer']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
            'manufacturers' => [],
            'manufacturer' => []
        ]);
    }
}