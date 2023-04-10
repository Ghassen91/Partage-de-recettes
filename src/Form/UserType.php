<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Votre email :'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom :'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class, 
                    'first_options' => [
                        'label' => 'Votre mot de passe :'
                    ],
                    'second_options' => [
                        'label' => 'Confirmez votre mot de passe :'
                    ]
            ])
            ->add('photoProfil', )
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}