<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
        ->add('address', EntityType::class, [
            'label' => 'Adresse de livraison',
            'class' => Logement::class,
            'required' => true,
            'choices' => $user->getLogement(),
            'multiple' => false,
            'expanded' => true,
            'attr' => [
                'class' => 'formAddress'
            ]
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Validez',
            'attr' => [
                'class' => 'btn'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => [],
        ]);
    }
}
