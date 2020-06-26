<?php

namespace App\Form;

use App\Entity\DataCapteurParJour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Form\CapteurType;

class DataCapteurParJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sendingDateTime', DateTimeType::class)
            ->add('level')
            ->add('capteur', CapteurType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DataCapteurParJour::class,
        ]);
    }
}
