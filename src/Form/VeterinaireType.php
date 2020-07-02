<?php

namespace App\Form;

use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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

class VeterinaireType extends AbstractType
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
                'label' => 'Nom',
            ])
                ->add('prenom', TextType::class, [
                    'label' => 'Prénom',
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                ])
            ->add('formations', TextareaType::class, [
                    'label' => 'Formations',
                    'attr' => [
                        'rows' => 4,
                    ]
                ])
            ->add('intervalBetweenRdv', TimeType::class, [
                'label' => 'Interval entre 2 rendez-vous',
                'attr' => [
                        'class' => 'flatpickr-time'
                ],
                'widget' => 'single_text',
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Compte actif ?',
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-custom'
                ],
            ])
            ->add('veterinaireHoraires', CollectionType::class, [
                'label' => 'Horaires',
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => VeterinaireHoraireType::class,
                'delete_empty' => true,
                'by_reference' => false,
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
