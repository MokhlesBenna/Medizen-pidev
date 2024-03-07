<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; // Import DateTimeType
use App\Entity\Docteur;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('address', TextType::class)
            ->add('mobile')




            ->add('reservation_date', DateTimeType::class, [
                'label' => 'Choisissez votre date de début ',
                'required' => true ,
                'data' => new \DateTime(),
                'widget' => 'single_text',
                
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date et l\'heure doivent être supérieures ou égales à la date actuelle.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                    'class' => 'form-control datetimepicker-input',
                ],
               
            ])

            
            ->add('problem_description', TextType::class) 
            ->add('doctor', EntityType::class, [
                'class' => Docteur::class,
                'choice_label' => function ($docteur) {
                    return $docteur->getNom() . ' ' . $docteur->getPrenom();
                },
                'placeholder' => 'Choisissez un docteur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
