<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,[
                'empty_data' => $options['data']->getTitre(),
            ])
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',  
                'empty_data' => $options['data']->getDateDebut(),
               
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu',null,[
                'empty_data' => $options['data']->getLieu(),
            ])
            ->add('description',null,[
                'empty_data' => $options['data']->getDescription(),
            ])
            ->add('image', FileType::class, [
                'label' => 'image (Des fichiers images uniquements)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'empty_data' => $options['data']->getImage(),

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
               
            ])
            // ...
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
