<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('synopsis')
            ->add('affiche')
            ->add('date')
            ->add('duree')
            ->add('nationalite')
            ->add('slug')
            ->add('legislation')
            ->add('trailer')
            ->add('genre_film')
            ->add('realise')
            ->add('acteur_joue')
            ->add('scenario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
