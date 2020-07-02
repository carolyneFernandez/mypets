<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Clinique;
use App\Entity\Rdv;
use App\Entity\Veterinaire;
use App\Repository\AnimalRepository;
use App\Repository\VeterinaireRepository;
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

class RdvStep2Type extends AbstractType
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
        $clinique = $rdv->getClinique();


        $builder->add('clinique', EntityType::class, [
            'label' => 'Clinique',
            'class' => Clinique::class,
            'choice_label' => 'nom',
            'attr' => [
                'class' => 'selectpicker',
                'data-live-search' => true,
                'data-style' => 'custom-control',
                'data-none-selected-text' => 'Choisissez une clinique',
            ],
            'choice_attr' => function(Clinique $choice, $key, $value) {
                $attr = [];
                if ($choice->getAvatar()) {
                    $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_clinique') . $choice->getAvatar() . '\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNom();
                } else {
                    $attr['data-content'] = '<img src=\'/assets/img/svg/health-clinic_50x50.svg\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNom();
                }

                return $attr;
            }

        ])
                ->add('veterinaire', EntityType::class, [
                    'label' => 'Vétérinaire',
                    'class' => Veterinaire::class,
                    'choice_label' => 'nomPrenom',
                    'attr' => [
                        'class' => 'selectpicker',
                        'data-live-search' => true,
                        'data-style' => 'custom-control',
                        'data-none-selected-text' => 'Choisissez un vétérinaire',
                    ],
                    'query_builder' => function(VeterinaireRepository $vr) use ($clinique) {
                        return $vr->createQueryBuilder('v')
                                  ->andWhere('v.clinique = :clinique')
                                  ->setParameter('clinique', $clinique)
                                  ->orderBy('v.nom', 'ASC')
                                  ->addOrderBy('v.prenom', 'ASC')
                                  ->andWhere('v.actif = 1')
                            ;
                    },
                    'choice_attr' => function(Veterinaire $choice, $key, $value) {
                        $attr = [];
                        if ($choice->getAvatar()) {
                            $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_user') . $choice->getAvatar() . '\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNomPrenom();
                        } else {
                            $attr['data-content'] = '<div class="text-center d-inline-block mr-2" style="width: 100px !important;"><span style="font-size: 50px;" class="align-middle"><i class="fas fa-user-circle"></i></span></div>' . $choice->getNomPrenom();
                        }

                        return $attr;
                    }

                ])
        ;


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            /** @var Rdv $rdv */
            $rdv = $event->getData();
            $form = $event->getForm();
            $clinique = $rdv['clinique'];
            if (!$rdv) {
                return;
            }

            // checks whether the user has chosen to display their email or not.
            // If the data was submitted previously, the additional value that is
            // included in the request variables needs to be removed.
            $form->add('veterinaire', EntityType::class, [
                'class' => Veterinaire::class,
                'query_builder' => function(VeterinaireRepository $vr) use ($clinique) {
                    return $vr->createQueryBuilder('v')
                              ->andWhere('v.clinique = :clinique')
                              ->setParameter('clinique', $clinique)
                              ->orderBy('v.nom', 'ASC')
                              ->addOrderBy('v.prenom', 'ASC')
                              ->andWhere('v.actif = 1')
                        ;
                },

            ]);
        });


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }

}
