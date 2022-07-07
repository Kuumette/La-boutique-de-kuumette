<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'deschamps@gmail.com'
                
            ]
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Deschamps'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de renseigner un nom',
                ]),
            ]
        ])
        ->add('firstName', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Patrique'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de renseigner un prénom',
                ]),
            ]
        ])
        ->add('phone', TextType::class, [
            'label' => 'Telephone (Français)',
            'attr' => [
                'placeholder' => '0600010203'
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mot de passes ne sont pas identiques',
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de renseigner un mot de passe',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'le mot de passe doit contenir au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 50,
                ]),
            ],
            'options' => [
                'attr' => [
                    'class' => 'password-field'
                ]
            ],
            
            'required' => true,
            'first_options'  => [
                'label' => 'Mot de passe (minimum 10 caractères 1 minuscule, 1 majuscule, 1 chiffre et 1 caractere spécial)',
                'attr' => [
                    'placeholder' => '******'
                ]
            ],
            'second_options' => [
                'label' => 'Confirme mot de passe',
                'attr' => [
                    'placeholder' => '******'
                ]
            ],
        ])
    
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
