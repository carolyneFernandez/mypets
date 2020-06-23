<?php

namespace App\Form\Profile;

use App\Entity\Proprietaire;
use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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

class ProfileProprietaireType extends ProfileUserType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adresse', TextareaType::class, [
            'label' => 'Adresse',
            'attr' => [
                'rows' => 3
            ],
        ])
                ->add('dateNaissance', DateType::class, [
                    'label' => 'Date de naissance',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'flatpickr-date',
                    ],
                ])
                ->add('telephone', TelType::class, [
                    'label' => 'Téléphone',
                    'required' => false
                ])
                ->add('mobile', TelType::class, [
                    'label' => 'Mobile',
                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }

}
