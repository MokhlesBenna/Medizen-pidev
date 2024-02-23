<?php 

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Docteur;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('address', TextType::class)
            ->add('mobile')
            ->add('reservation_date')
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
