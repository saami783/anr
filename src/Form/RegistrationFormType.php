<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Street;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{

    /**
     * Construit le formulaire d'inscription avec des champs pour l'utilisateur et l'adresse.
     * Les champs d'adresse (ville, rue, numéro) ne sont pas mappés et sont gérés dynamiquement
     * en fonction de la sélection de la ville.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'mapped' => true
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les CGU',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])

            // Champ lié à l'entité Address.
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
                'mapped' => false,
                'label' => 'Rue',
            ])
            ->add('number', TextType::class, [
                'label' => 'Numéro de rue',
                'required' => true,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn-secondary']
            ])
            ;
        $builder->get('city')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->getParent()->add('street', EntityType::class, [
                    'class' => Street::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Sélectionnez une rue',
                    'mapped' => false,
                    'choices' => $form->getData()->getStreets()
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['user']
        ]);
    }
}
