<?php

namespace App\Form;

use App\Entity\Sponseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SponseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('numero')
            ->add('logo')
            ->add('pack', ChoiceType::class, [
                'choices' => [
                    'Or' => 'or',
                    'Bronze' => 'Bronze',
                    'Argent' => 'Argent',
                ],
                'placeholder' => 'Sélectionnez un pack',
                'label' => 'Pack',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponseur::class,
        ]);
    }
}
