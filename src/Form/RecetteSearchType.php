<?php

namespace App\Form;

use App\DTO\RecetteCriteria;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Nom de la recette :',
                'required' =>false
            ])
            ->add('orderBy', ChoiceType::class, [
                'label' => 'Trier par :',
                'required' => false,
                'empty_data' => 'createdAt',
                'choices' => [
                    'Date' => 'createdAt',
                    'Nom' => 'titre'
                ]
            ])
            ->add('limit', NumberType::class, [
                'label' => 'Limite :',
                'required' => false,
                'empty_data' => 25
            ])
            ->add('direction', ChoiceType::class, [
                'label' => 'Sens du tri :',
                'empty_data' => 'DESC',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
                'required' => false
            ])
            ->add('user', TextType::class, [
                'label' => 'Posté par :',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecetteCriteria::class,
            'method' => 'GET',
            'empty_data' => new RecetteCriteria(),
            'data' => new RecetteCriteria(),
            'csrf_protection' => false
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}