<?php

namespace App\Form;

use App\Entity\Oeuvreart;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OeuvreartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('titre' , TextType::class, [
                'label' => 'Titre', 
                'required' => true, 
            ])

            ->add('idArtiste', EntityType::class, [
                'label' => 'Artiste',
                'class' => Utilisateur::class,
                'choice_label' => function ($utilisateur) {
                    return ' Num Cin: ' .$utilisateur->getCin() . ' - ' .$utilisateur->getNom() . ' ' . $utilisateur->getPrenom();
                },
                'placeholder' => 'Sélectionner Artiste', 
                'required' => true, 
            ])
            ->add('description',TextareaType::class, [
                'required' => true,
                'label' => 'Description', 
                'attr' => ['class' => 'form-control', 'rows' => 4], 
            ])
            
            ->add('prixvente')
            
            ->add('dateajout', DateType::class, [
                'widget' => 'single_text',
                'data' => (new \DateTime())->setTime(0, 0, 0), 
            ])
            ->add('idCategorie', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Categorie::class,
                'choice_label' => 'nomcategorie',
                'placeholder' => 'Sélectionner une catégorie', 
                'required' => true, 
            ])

            ->add('image', FileType::class, 
            array('data_class' => null))
           
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvreart::class,
        ]);
    }
    
}
