<?php

namespace App\Form;

use App\Entity\Oeuvreart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;

class ArtisteOeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'data_class' => null,
            'attr' => [
                'placeholder' => 'Sélectionnez une image',
            ],
        ])


        ->add('titre', TextType::class, [
            'label' => 'Titre',
            'required' => true,
            'attr' => [
                'placeholder' => 'Entrez le titre ici',
            ],
        ])
        ->add('idCategorie', EntityType::class, [
            'label' => 'Catégorie',
            'class' => Categorie::class,
            'choice_label' => 'nomcategorie',
            'placeholder' => 'Sélectionner une catégorie', 
            'required' => true, 
        ])

        ->add('description', TextareaType::class, [
            'required' => true,
            'label' => 'Description',
            'attr' => [
                 'class' => 'form-control',
                 'rows' => 5,
                 'placeholder' => 'Entrez la description ici',
          ],
        ])

        ->add('prixvente', null, [
            'attr' => [
                 'placeholder' => 'Entrez le prix de vente',
             ],
        ])
        
        ->add('dateajout', DateType::class, [
                'widget' => 'single_text',
                'data' => (new \DateTime())->setTime(0, 0, 0), 
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvreart::class,
        ]);
    }
}
