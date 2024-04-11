<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('numtel')
            ->add('cin')
            ->add('image')
            ->add('genre',ChoiceType::class, [
        'choices'  => [
            'Homme' => 'Homme',
            'Femme' => 'Femme',
            // Ajoutez ici d'autres options
        ],
        // Optionnel : définissez ici d'autres options de champ
    ])
            ->add('datenaissance',BirthdayType::class , [
                 'widget' => 'single_text',
                 'format' => 'yyyy-MM-dd',
            ])
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
