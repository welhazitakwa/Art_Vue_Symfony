<?php
// src/Form/ConcoursType.php

namespace App\Form;

use App\Entity\Concours;
use App\Entity\Oeuvreart; // Import de l'entité Oeuvreart
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Import du type de champ CheckboxType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


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
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'data' => (new \DateTime())->setTime(0, 0, 0), 
        ])
        ->add('dateFin',  DateType::class, [
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'data' => (new \DateTime())->setTime(0, 0, 0), 
        ])
      
        
        ->add('description', TextareaType::class, [
            'label' => 'Description du concours',
            'attr' => ['class' => 'form-control', 'rows' => 4], 
            // Autres options de champ de description
        ])
        ->add('oeuvres', EntityType::class, [
            'class' => Oeuvreart::class,
            'choice_label' => 'titre', // Utilisez directement le champ 'titre' de l'entité Oeuvreart
            'multiple' => true,
            'expanded' => true,
            'by_reference' => false,
            // Autres options
        ]);
        
        
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }
}
