<?php

namespace App\Form\Profile;

use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Clinique;

class ProfileVeterinaireType extends ProfileUserType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('intervalBetweenRdv', TimeType::class, [
            'label' => 'Interval entre 2 rendez-vous',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'flatpickr-time',
            ],
        ])
                ->add('formations', TextareaType::class, [
                    'label' => 'Formations',
                    'attr' => [
                        'rows' => 5,
                        'placeholder' => 'Décrivez votre spécialité, formations, etc'
                    ]
                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Veterinaire::class,
        ]);
    }

}
