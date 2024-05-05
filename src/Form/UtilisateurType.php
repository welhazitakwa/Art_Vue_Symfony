<?php

namespace App\Form;

use App\Entity\Utilisateur;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateActuelle = new DateTime();
        $builder
            ->add('nom' , TextType::class, [
                'required' => false,
            ])
            ->add('prenom' , TextType::class, [
                'required' => false,
            ] )
            ->add('email' , TextType::class, [
                'required' => false,
            ])
            ->add('login' , TextType::class, [
                'required' => false,
            ])
            ->add('mdp',PasswordType::class , [
                'required' => false,
            ])
            ->add('profil',ChoiceType::class, [
            'choices' => [
                'Je suis un client' => '2',
                'Je suis un artiste' => '1',
            ],
            'expanded' => true, // Afficher comme des boutons radio
            'multiple' => false, // seulement un choix
                 ])
            ->add('dateInscription', DateType::class, [
                                    'widget' => 'single_text',
                                    'data' => $dateActuelle,
                                    'attr' => ['hidden' => true],
                                    'label' => ''
                                                    ]) 
            ->add('etatCompte', TextType::class, [
                                'data' => 0, 
                                'attr' => ['hidden' => true],
                                                ])
            ->add('image', FileType::class, [
                'label' => ' ',
                'required' => false, // L'image n'est pas obligatoire
                'attr' => ['hidden' => true],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
