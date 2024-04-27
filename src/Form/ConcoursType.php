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
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Count;





class ConcoursType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Titre du concours',
            // Autres options du champ titre
            'constraints' => [
                new Regex([
                    'pattern' => '/^[A-Za-z\s]+$/',
                    'message' => 'Le titre ne peut contenir que des lettres.',
                ]),
            ],
        ])
        ->add('dateDebut', DateType::class, [
            'label' => 'Date de Debut',
            'widget' => 'single_text',
            'data' => (new \DateTime())->setTime(0, 0, 0), 
            'constraints' => [
                new Callback([$this, 'validateDateDebut']),
            ],

        ])
        ->add('dateFin',  DateType::class, [
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'data' => (new \DateTime())->setTime(0, 0, 0), 
            'constraints' => [
                new Callback([$this, 'validateDateFin']),
            ],
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
            'constraints' => [
                new Count(['min' => 2, 'minMessage' => 'Veuillez sélectionner au moins deux œuvres.']),
            ],
            
        ]);
        
        
            
        
    }
   
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }

    public function validateDateFin($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $concours = $form->getData();
        $dateDebut = $concours->getDateDebut();
        $dateFin = $value;
    
        if ($dateDebut > $dateFin) {
            $context->buildViolation('La date de fin doit être postérieure à la date de début.')
                ->atPath('dateFin')
                ->addViolation();
        }
    }
    public function validateDateDebut($value, ExecutionContextInterface $context)
{
    $now = new \DateTime();
    $dateDebut = $value;

    if ($dateDebut < $now) {
        $context->buildViolation('La date de début doit être postérieure ou égale à la date d\'aujourd\'hui.')
            ->atPath('dateDebut')
            ->addViolation();
    }
}

    
}
