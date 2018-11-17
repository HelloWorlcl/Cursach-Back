<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mark', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('model', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('release_year', IntegerType::class)
            ->add('mileage', IntegerType::class)
            ->add('engine_capacity', IntegerType::class)
            ->add('fuel', ChoiceType::class, [
                'choices' => [
                    'petrol', 'diesel', 'gas', 'electric', 'hybrid', 'other'
                ]
            ])
            ->add('transmission', ChoiceType::class, [
                'choices' => [
                    'manual', 'automatic', 'automated_manual', 'continuously_variable', 'other'
                ]
            ])
            ->add('drive_unit', ChoiceType::class, [
                'choices' => [
                    'front-wheel', 'rear', 'four-wheel', 'other'
                ]
            ])
            ->add('owner', EntityType::class, ['class' => Client::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'allow_extra_fields' => true
        ]);
    }
}
