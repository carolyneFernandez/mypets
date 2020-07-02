<?php

namespace App\Form;

use App\Entity\Clinique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

class CliniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
                'label' => 'Nom de la clinique'
            ])
                ->add('siret', TextType::class, [
                    'label' => 'SIRET'
                ])
                ->add('adresse', TextareaType::class, [
                    'label' => 'Adresse',
                    'attr' => [
                        'rows' => 3,
                    ]
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email'
                ])
                ->add('telephone', TelType::class, [
                    'label' => 'Téléphone'
                ])
            ->add('rdvDomicile', CheckboxType::class, [
                    'label' => 'Proposez-vous des rendez-vous à domicile ?',
                    'required' => false,
                    'label_attr' => [
                        'class' => 'checkbox-custom'
                    ]
                ])
            ->add('avatar', FileType::class, [
                    'label' => 'Avatar',
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
            ->add('cliniqueHoraires', CollectionType::class, [
                'label' => 'Horaires',
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => CliniqueHoraireType::class,
                'delete_empty' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clinique::class,
        ]);
    }
}
