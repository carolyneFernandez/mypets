<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Clinique;
use App\Entity\Proprietaire;
use App\Entity\Rdv;
use App\Entity\Veterinaire;
use App\Repository\AnimalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class RdvType extends AbstractType
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
        /** @var Rdv $rdv */
        $rdv = $options['data'];
        $proprietaire = $rdv->getProprietaire();

        if ($this->security->isGranted($this->container->getParameter('ROLE_CLINIQUE'))) {
            $builder->add('proprietaire', EntityType::class, [
                'label' => 'Propriétaire',
                'class' => Proprietaire::class,
                'choice_label' => 'nomPrenom',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-style' => 'custom-control',
                    'data-none-selected-text' => 'Sélectionnez un propriétaire'
                ],
                'choice_attr' => function(Proprietaire $choice, $key, $value) {
                    $attr = [];
                    if ($choice->getAvatar()) {
                        $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_user') . $choice->getAvatar() . '\' alt="photo" width="100px" class="mr-2 rounded-pill">' . $choice->getNomPrenom() . ' - ' . $choice->getEmail();
                    } else {
                        $attr['data-content'] = '<div class="text-center d-inline-block mr-2" style="width: 100px !important;"><img src=\'/assets/img/svg/user-circle-solid.svg\' alt="photo" height="50px" class="rounded-pill"></div>' . $choice->getNomPrenom() . ' - ' . $choice->getEmail();
                    }
                    return $attr;
                }

            ]);
        }
        $builder->add('animal', EntityType::class, [
            'label' => 'Animal',
            'class' => Animal::class,
            'choice_label' => 'nom',
            'attr' => [
                'class' => 'selectpicker',
                'data-live-search' => true,
                'data-style' => 'custom-control',
                'data-none-selected-text' => 'Sélectionnez un animal'
            ],
            'query_builder' => function(AnimalRepository $ar) use ($proprietaire) {
                return $ar->createQueryBuilder('a')
                          ->andWhere('a.proprietaire = :proprietaire')
                          ->setParameter('proprietaire', $proprietaire)
                          ->andWhere('a.decede = false')
                    ;
            },
            'choice_attr' => function(Animal $choice, $key, $value) {
                $attr = [];
                if ($choice->getPhoto()) {
                    $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_animal') . $choice->getPhoto() . '\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNom();
                } else {
                    $attr['data-content'] = '<div class="text-center d-inline-block mr-2" style="width: 100px !important;"><span style="font-size: 50px;" class="align-middle"><i class="fas fa-paw"></i></span></div>' . $choice->getNom();
                }

                return $attr;
            }
        ]);


        if ($this->security->isGranted($this->container->getParameter('ROLE_CLINIQUE'))) {

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                /** @var Rdv $rdv */
                $rdv = $event->getData();
                $form = $event->getForm();
                $proprietaire = $rdv['proprietaire'];
                if (!$rdv) {
                    return;
                }

                // checks whether the user has chosen to display their email or not.
                // If the data was submitted previously, the additional value that is
                // included in the request variables needs to be removed.
                $form->add('animal', EntityType::class, [
                    'class' => Animal::class,
                    'query_builder' => function(AnimalRepository $ar) use ($proprietaire) {
                        return $ar->createQueryBuilder('a')
                                  ->andWhere('a.proprietaire = :proprietaire')
                                  ->setParameter('proprietaire', $proprietaire)
                                  ->andWhere('a.decede = false')
                            ;
                    },

                ]);
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }

}
