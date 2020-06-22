<?php

namespace App\Form;

use App\Entity\CliniqueHoraire;
use App\Entity\Veterinaire;
use App\Entity\VeterinaireHoraire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

class CliniqueHoraireType extends AbstractType
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
        $builder->add('jour', ChoiceType::class, [
            'label' => 'Jour de la semaine',
            'choices' => [
                'Lundi' => 1,
                'Mardi' => 2,
                'Mercredi' => 3,
                'Jeudi' => 4,
                'Vendredi' => 5,
                'Samedi' => 6,
                'Dimanche' => 7,
            ],
            'attr' => [
                'class' => 'selectpicker',
                'data-style' => 'form-control',
            ]
        ])
                ->add('heureDebut', TimeType::class, [
                    'label' => 'Heure de début',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'flatpickr-time',
                    ],
                    'view_timezone' => 'Europe/Paris',
                ])
                ->add('heureFin', TimeType::class, [
                    'label' => 'Heure de fin',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'flatpickr-time',
                    ],
                    'view_timezone' => 'Europe/Paris',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CliniqueHoraire::class,
        ]);
    }

}
