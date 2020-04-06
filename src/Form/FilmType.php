<?php

namespace App\Form;

use App\Entity\Film;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('synopsis',TextareaType::class,['label' => 'Synopsis'])
            ->add('affiche',TextType::class)
            ->add('date',DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('duree',NumberType::class,['label' => 'Durée'])
            ->add('nationalite',TextType::class,['label' => 'Nationalité'])
            ->add('legislation',ChoiceType::class,[
                'choices' => [
                    'Tous Publics' => "touspublics",
                    'Interdit -10 ans' => "i-10",
                    'Interdit -12 ans' => "i-12",
                    'Interdit -16 ans' => "i-16",
                    "Interdit -18 ans" => "i-18"
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => "CNC"
            ])
            ->add('trailer',TextType::class)
            ->add('genre',TextType::class,["mapped" => false])
            ->add('real',TextType::class,["mapped" => false])
            ->add('scen',TextType::class,["mapped" => false])
            ->add('act',TextType::class,["mapped" => false])
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
