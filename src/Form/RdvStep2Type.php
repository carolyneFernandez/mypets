<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Clinique;
use App\Entity\Rdv;
use App\Entity\Veterinaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
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
        $builder->add('clinique', EntityType::class, [
            'label' => 'Clinique',
            'class' => Clinique::class,
            'choice_label' => 'nom',
            'attr' => [
                'class' => 'selectpicker',
                'data-live-search' => true,
                'data-style' => 'custom-control',
            ],
            'mapped' => false,
            'choice_attr' => function(Clinique $choice, $key, $value) {
                $attr = [];
                if ($choice->getAvatar()) {
                    $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_clinique') . $choice->getAvatar() . '\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNom();
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
                    ],
                    'choice_attr' => function(Veterinaire $choice, $key, $value) {
                        $attr = [];
                        if ($choice->getAvatar()) {
                            $attr['data-content'] = '<img src=\'/' . $this->container->getParameter('dir_avatar_user') . $choice->getAvatar() . '\' alt=\'photo\' width="100px" class="mr-2 rounded-pill">' . $choice->getNom();
                        }

                        return $attr;
                    }

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }

}
