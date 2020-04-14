<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('roles',CollectionType::class,[
                'entry_type' => HiddenType::class
            ])
            ->add('password',PasswordType::class)
            ->add('email')
            ->add('avatar',TextType::class,['required' => false])
            ->add('image',FileType::class,[
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => '.jpeg,.jpg,.png'
                ]
                ])
            ->add('ban',HiddenType::class)
            ->add('oldpassword',TextType::class,['mapped' => false,'required' => false])
            ->add('newpassword',PasswordType::class,['mapped' => false,'required' => false])
            ->add('confirmpassword',PasswordType::class,['mapped' => false,'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
