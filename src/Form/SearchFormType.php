<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Street;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Choisir une ville',
                'label' => 'Ville',
            ])
            ->add('street', EntityType::class, [
                'class' => Street::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Choisir une rue',
                'label' => 'Rue',
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
