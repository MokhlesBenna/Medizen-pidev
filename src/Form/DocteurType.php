<?php

namespace App\Form;

use App\Entity\Docteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class DocteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est requis.']),
                ],
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse email est requise.']),
                    new Email(['message' => 'Veuillez fournir une adresse email valide.']),
                ],
            ])
            ->add('experience', TextType::class, [
                'label' => 'Expérience',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'L\'expérience est requise.']),
                ],
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Mobile',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de mobile est requis.']),
                    new Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de mobile doit contenir exactement 8 chiffres.',
                    ]),
                ],
            ])
            ->add('addresse', TextareaType::class, [
                'label' => 'Adresse',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse est requise.']),
                ],
            ])
            ->add('specialite', TextType::class, [
                'label' => 'Spécialité',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La spécialité est requise.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Docteur::class,
        ]);
    }
}
