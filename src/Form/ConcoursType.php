<?php
// src/Form/ConcoursType.php

namespace App\Form;

use App\Entity\Concours;
use App\Entity\Oeuvreart; // Import de l'entité Oeuvreart
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du concours',
                // Autres options du champ titre
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'form-control datepicker' // Ajoutez la classe "form-control" pour une meilleure apparence de formulaire Bootstrap
                ]
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'form-control datepicker' // Ajoutez la classe "form-control" pour une meilleure apparence de formulaire Bootstrap
                ]
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Description du concours',
                // Autres options de champ de description
            ])
            // Champ pour sélectionner les œuvres associées au concours
            ->add('oeuvres', EntityType::class, [
                'class' => Oeuvreart::class, // Entité cible pour les œuvres
                'choice_label' => 'titre', // Propriété de l'entité à afficher dans le champ
                'multiple' => true, // Permettre la sélection de plusieurs œuvres
                'expanded' => true, // Afficher les œuvres sous forme de cases à cocher
                // Ajoutez d'autres options selon vos besoins
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }
}
