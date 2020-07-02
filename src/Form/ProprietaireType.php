<?php

namespace App\Form;

use App\Entity\Proprietaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ProprietaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
                ->add('prenom', TextType::class, [
                    'label' => 'Prénom'
                ])
            ->add('adresse',  TextareaType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'rows' => 3,
                ],
            ])
                ->add('dateNaissance', DateType::class, [
                    'label' => 'Date de naissance',
                    'attr' => [
                        'class' => 'flatpickr-date'
                    ],
                    'widget' => 'single_text',
                ])
                ->add('telephone', TelType::class, [
                    'label' => 'Téléphone',
                    'required' => false,
                ])
                ->add('mobile', TelType::class, [
                    'label' => 'Mobile',
                    'required' => false,
                ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Actif ?',
                'label_attr' => [
                    'class' => 'checkbox-custom'
                ],
                'required' => false,
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
