<?php

namespace App\Form;

use App\Entity\Offreenchere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreenchereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offreenchere::class,
        ]);
    }
}
