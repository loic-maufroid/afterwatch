<?php

namespace App\Form;

use App\Entity\Film;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{

    private $genreRepository;

    public function __construct(GenreRepository $genreRepository)
    {
     $this->genreRepository = $genreRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,['label' => 'Titre'])
            ->add('synopsis',HiddenType::class)
            ->add('affiche',HiddenType::class)
            ->add('date',DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('duree',HiddenType::class)
            ->add('nationalite',HiddenType::class)
            ->add('legislation')
            ->add('trailer',HiddenType::class)
            ->add('genre',HiddenType::class,["mapped" => false])
            ->add('real',HiddenType::class,["mapped" => false])
            ->add('scen',HiddenType::class,["mapped" => false])
            ->add('act',HiddenType::class,["mapped" => false])
        ;

        
        $builder->addEventListener(FormEvents::PRE_SUBMIT,function (FormEvent $event){
         
            $form = $event->getForm();
            $data = $event->getData();
            dump($data);
            dump($form);
            
              
            //$form->getData()->setTitre($data["titre"]);
            //$form->getData()->setSynopsis($data["synopsis"]);
            //dump($data["date"]);
            //$form->getData()->setDate(\DateTime::createFromFormat('yyyy-mm-dd',$data["date"]));
           

        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
