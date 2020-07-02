<?php

namespace App\Form;

use App\Entity\Clinique;
use App\Entity\Secretaire;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
                'label' => 'Nom de la clinique',
            ])
                ->add('adresse', TextareaType::class, [
                    'label' => 'Adresse de la clinique',
                    'attr' => [
                        'rows' => 3,
                    ],
                ])
                ->add('rdvDomicile', ChoiceType::class, [
                    'label' => 'Prenez-vous des rendez-vous à domicile ?',
                    'choices' => [
                        'Non' => false,
                        'Oui' => true,
                    ],
                    'expanded' => true,
                    'label_attr' => [
                        'class' => 'label required'
                    ]
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse email de la clinique (servira comme première adresse de connexion',
                ])
                ->add('telephone', TelType::class, [
                    'label' => 'Numéro de téléphone',
                    'attr' => [
                        'class' => 'rounded-pill'
                    ]
                ])
                ->add('siret', TextType::class, [
                    'label' => 'N° de siret',
                ])
                ->add('secretaires', CollectionType::class, [
                    'allow_add' => false,
                    'allow_delete' => false,
                    'delete_empty' => false,
                    'entry_type' => SecretaireType::class,
                ])
                ->add('agreeTerms', CheckboxType::class, [
                    'label' => 'En cochant cette case vous certifiez que vous êtes une clinique aggréée.',
                    'mapped' => false,
                    'label_attr' => [
                        'class' => 'checkbox-custom'
                    ]
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
