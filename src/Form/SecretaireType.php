<?php

namespace App\Form;

use App\Entity\Secretaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecretaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email')
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passes doivent correspondre.',
                    'required' => true,
                    'first_options' => ['label' => 'Mot de passe du compte'],
                    'second_options' => ['label' => 'Confirmez le mot de passe'],
                    'mapped' => false,
                ])
                ->add('nom', TextType::class, [
                    'label' => 'Votre nom',
                ])
                ->add('prenom', TextType::class, [
                    'label' => 'Votre prénom',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Secretaire::class,
        ]);
    }

}
