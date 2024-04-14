<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateajout', DateType::class, [
                'widget' => 'single_text',
                'data' => (new \DateTime())->setTime(0, 0, 0), 
            ]) 
            ->add('client', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // Remplacez 'nom' par le nom de la propriété de l'entité Utilisateur que vous souhaitez afficher dans le champ
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
