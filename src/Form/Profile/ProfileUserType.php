<?php

namespace App\Form\Profile;

use App\Entity\Secretaire;
use App\Entity\User;
use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
use Symfony\Component\Validator\Constraints\File;

class ProfileUserType extends AbstractType
{
    private $container;
    private $security;

    /**
     * EvenementType constructor.
     * @param Security $security
     * @param $container
     */
    public function __construct(Security $security, ContainerInterface $container)
    {
        $this->security = $security;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom'
        ])
                ->add('prenom', TextType::class, [
                    'label' => 'Prénom'
                ])
                ->add('email', TextType::class, [
                    'label' => 'Email'
                ])
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passes doivent correspondre.',
                    'required' => false,
                    'first_options' => ['label' => 'Nouveau mot de passe'],
                    'second_options' => ['label' => 'Confirmez le mot de passe'],
                    'mapped' => false,
                ])
                ->add('photo', FileType::class, [
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
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
