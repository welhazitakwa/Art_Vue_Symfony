<?php

namespace App\Form;

use App\Entity\Oeuvreart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OeuvreartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image')
            ->add('titre')
            ->add('description')
            ->add('prixvente')
            ->add('status')
            ->add('dateajout')
            ->add('idCategorie')
            ->add('idArtiste')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvreart::class,
        ]);
    }
}
