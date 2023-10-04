<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints as Assert;


class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control w-25'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label_attr' => [
                        'class' => 'form-label mt-2'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

}