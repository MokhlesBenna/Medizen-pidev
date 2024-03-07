<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartementType extends AbstractType
{
    private $etablissementRepository;

    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('description')
            ->add('chefDepartement')
            ->add('servicesOfferts')
            ->add('localisation')
            ->add('etablissement', ChoiceType::class, [
                'choices' => $this->getEtablissementChoices(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'Choose Etablissement'
            ]);
    }

    private function getEtablissementChoices()
    { 
        $etablissements = $this->etablissementRepository->findAll();

        $choices = [];
        foreach ($etablissements as $etablissement) {
            $choices[$etablissement->getName()] = $etablissement;
        }
    
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Departement::class,
        ]);
    }
}
