<?php

namespace App\Form;

use App\Entity\ConsultationPatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ConsultationPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    
            ->add('name')
            ->add('surname')
            ->add('remarquesDesDocteurs', TextareaType::class, [
                'label' => 'Remarques des docteurs',
                'attr' => ['rows' => 5] 
            ])
            ->add('reservation_date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConsultationPatient::class,
        ]);
    }
}
