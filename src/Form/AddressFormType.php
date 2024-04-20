<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\City;
use App\Entity\Street;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une ville',
                'required' => true,
                'mapped' => false,
                'label' => 'Ville',
            ])
            ->add('street', EntityType::class, [
                'class' => Street::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une rue',
                'choices' => [],
                'required' => true,
                'label' => 'Rue',
            ])
            ->add('number', TextType::class, [
                'label' => 'Numéro de rue',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => "Sauvegarder",
            ])
        ;
        $builder->get('city')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->getParent()->add('street', EntityType::class, [
                    'class' => Street::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Sélectionnez une ville',
                    'mapped' => false,
                    'choices' => $form->getData()->getStreets()
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
