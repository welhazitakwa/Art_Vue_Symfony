<?php

namespace App\Form;

use App\Entity\Venteencheres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteencheresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedebut')
            ->add('datefin')
            ->add('prixdepart')
            ->add('statue')
            ->add('idExposition', EntityType::class, [
                'class' => 'App\Entity\Exposition', // Entité cible
                'choice_label' => 'nom', // Propriété de l'entité à afficher dans le champ
            ])
            ->add('idUtilisateur', EntityType::class, [
                'class' => 'App\Entity\Utilisateur', // Entité cible
                'choice_label' => 'id', // Propriété de l'entité à afficher dans le champ
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Venteencheres::class,
        ]);
    }
}


