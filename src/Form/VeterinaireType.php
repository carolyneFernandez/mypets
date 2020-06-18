<?php

namespace App\Form;

use App\Entity\Veterinaire;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email'
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Compte actif ?',
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('intervalBetweenRdv', TimeType::class, [
                'label' => 'Interval entre rendez-vous'
            ])
            ->add('formations', TextType::class, [
                'label' => 'Formations'
            ])
        ;

        if($this->security->isGranted('ROLE_ADMIN')){
            $builder
                ->add('clinique', EntityType::class, [
                    'label' => 'Clinique',
                    'class' => Clinique::class,
                    'choice_label' => function($clinique){
                        return $clinique->getNom();
                    }
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Veterinaire::class,
        ]);
    }
}
