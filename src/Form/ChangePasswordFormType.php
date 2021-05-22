<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options["current_password_is_required"]) {
            $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => "Le mot de passe actuel",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis.',
                    ]),
                    new UserPassword(['message' => "Mot de passe incorrect."]),
                    ],
            ]);
        }

        $builder
            ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Ce champ est requis.',
                            ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Le mot de passe ne doit pas être inférieure à {{ limit }} caractères.',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                        ],
                        'label' => 'Le nouveau mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le nouveau mot de passe',
                    ],
                    'invalid_message' => 'Les mots de passe saisis ne sont pas identiques.',
                    // Instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "current_password_is_required" => false
        ]);
    }
}
