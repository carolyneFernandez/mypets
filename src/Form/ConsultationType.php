<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Clinique;
use App\Entity\Veterinaire;
use App\Entity\Animal;
use App\Entity\Rdv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AnimalRepository;
use App\Repository\RdvRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;



class ConsultationType extends AbstractType
{
    private $security;

    private $container;

    /**
     * RdvType constructor.
     * @param Security $security
     * @param ContainerInterface $container
     */
    public function __construct(Security $security, ContainerInterface $container)
    {
        $this->security = $security;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('compteRendu', TextareaType::class, [
                'label' => 'Compte rendu'
            ])
            ->add('animal', EntityType::class, [
                'label' => 'Animal',
                'class' => Animal::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-style' => 'custom-control',
                    'data-none-selected-text' => 'Sélectionnez un animal'
                ],
                'choice_attr' => function(Animal $animal, $key, $value){
                    $attr = [];
                    if ($animal->getPhoto()) {
                        $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_animal') . $animal->getPhoto() . '\' alt="photo" width="100px" class="mr-2 rounded-pill">' . $animal->getNom();
                    } else {
                        $attr['data-content'] = '<div class="text-center d-inline-block mr-2" style="width: 100px !important;"><img src=\'/assets/img/svg/user-circle-solid.svg\' alt="photo" height="50px" class="rounded-pill"></div>' . $animal->getNom();
                    }
                    return $attr;
                },
                'query_builder' => function(AnimalRepository $ar){
                    return $ar->createQueryBuilder('a')
                              ->andWhere('a.veterinaireHabituel = :veterinaire')
                              ->setParameter('veterinaire', $this->security->getUser())
                    ;
                }
            ])
            ->add('rdv', EntityType::class, [
                'label' => 'Pour quel rendez-vous ?',
                'class' => Rdv::class,
                'choice_label' => function(Rdv $rdv){
                    return $rdv->getDate()->format('d/m/Y H:s');
                },
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-style' => 'custom-control',
                    'data-none-selected-text' => 'Sélectionnez un rendez-vous'
                ],
                'query_builder' => function(RdvRepository $rdv){
                    return $rdv->createQueryBuilder('r')
                               ->andWhere('r.veterinaire = :veterinaire')
                               ->setParameter('veterinaire', $this->security->getUser())
                    ;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
