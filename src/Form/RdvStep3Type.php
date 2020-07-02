<?php

namespace App\Form;

use App\Entity\Animal;
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

class RdvStep3Type extends AbstractType
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

        if($options['edit'] === false || $this->security->isGranted($this->container->getParameter('ROLE_VETERINAIRE')) || $this->security->isGranted($this->container->getParameter('ROLE_CLINIQUE')) || !$rdv->getValide())

        $builder->add('date', DateTimeType::class, [
            'label' => 'Date',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'flatpickr-datetime'
            ]
        ])
                ->add('observations', TextareaType::class, [
                    'label' => 'Observations',
                    'attr' => [
                        'placeholder' => 'Notes que vous trouverez utile à nous communiquer'
                    ]
                ])
        ;

        if ($options['edit'] === true && ($this->security->isGranted($this->container->getParameter('ROLE_VETERINAIRE')) || $this->security->isGranted($this->container->getParameter('ROLE_CLINIQUE'))) ) {
            $builder->add('valide', CheckboxType::class, [
                'label' => 'Validé',
                'label_attr' => [
                    'class' => 'checkbox-custom',
                ],
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
            'edit' => false,
        ]);
    }

}
