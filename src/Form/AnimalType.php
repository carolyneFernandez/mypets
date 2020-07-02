<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Proprietaire;
use App\Entity\Veterinaire;
use App\Entity\AnimalType as TypeAnimal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Importer un fichier valide (.jpeg, .png ou .svg )'
                    ])
                ],
                'mapped' => false,
                'help' => 'Image < 10 Mo',
                'required' => false,
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'attr' => [
                    'class' => 'flatpickr-date'
                ],
                'widget' => 'single_text',
            ])
            ->add('race', TextType::class, [
                'label' => 'Race'
            ])
            ->add('puce', TextType::class, [
                'label' => 'Puce'
            ])
            ->add('infosPere', TextType::class, [
                'label' => 'Information du père'
            ])
            ->add('infosMere', TextType::class, [
                'label' => 'Information de la mère'
            ])
            ->add('traitements', TextType::class, [
                'label' => 'Traitements'
            ])
            ->add('decede', CheckboxType::class, [
                'label' => 'Décédé ?',
                'label_attr' => [
                    'class' => 'checkbox-custom'
                ],
                'required' => false,
            ])
            ->add('dateDeces', DateType::class, [
                'label' => 'Date de décès',
                'attr' => [
                    'class' => 'flatpickr-date'
                ],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('causeDeces',TextType::class, [
                'label' => 'Cause du décès',
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'label' => "Type d'animal",
                'class' => TypeAnimal::class,
                'choice_label' => function(TypeAnimal $typeAnimal){
                    return $typeAnimal->getNom();
                },
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-size' => 10,
                    'data-style' => 'form-control',
                    'data-none-selected-text' => 'Aucun type sélectionné'
                ]
            ])
            ->add('veterinaireHabituel', EntityType::class, [
                'label' => 'Vétérinaire habituel',
                'class' => Veterinaire::class,
                'choice_label' => function (Veterinaire $veterinaire){
                    return $veterinaire->getNom() . ' ' . $veterinaire->getPrenom();
                },
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-size' => 10,
                    'data-style' => 'form-control',
                    'data-none-selected-text' => 'Aucune enseigne sélectionnée'
                ]
            ])
            ->add('Proprietaire', EntityType::class, [
                'label' => 'Propriétaire',
                'class' => Proprietaire::class,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-size' => 10,
                    'data-style' => 'form-control',
                    'data-none-selected-text' => 'Aucune enseigne sélectionnée'
                ],
                'choice_label' => function(Proprietaire $proprietaire){
                    return $proprietaire->getNom() . ' ' . $proprietaire->getPrenom();
                }

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
