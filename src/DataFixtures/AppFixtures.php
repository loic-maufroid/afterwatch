<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Acteur;
use App\Entity\Commentaire;
use App\Entity\Critique;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Note;
use App\Entity\Realisateur;
use App\Entity\Scenariste;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Créer les Utilisateurs
        $utilisateurs = [];
        for ($i = 1; $i <=20; ++$i)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setUsername('utilisateur '.$i);
            $utilisateur->setEmail($faker->email);
            $utilisateur->setPassword('password');
            $manager->persist($utilisateur);
            $utilisateurs[] = $utilisateur;
        }

        //Créer les Films
        $films = [];
        for ($i = 1; $i <=75; ++$i)
        {
            $film = new Film();
            $film->setTitre('Titre Film '.$i);
            $film->setSynopsis($faker->text);
            $film->setSlug($this->slugger->slug($film->getTitre())->lower());
            $manager->persist($film);
            $films[] = $film;
        }

        //Créer les Genres
        $categories = ['Aventure', 'Horreur', 'Comédie'];
        $genres = [];
        foreach ($categories as $categorie)
        {
            $genre = new Genre();
            $genre->setType($categorie);
            $genre->addFilm($faker->randomElement($films));
            $manager->persist($genre);
            $genres[] = $genre;
        }


        //Créer les Acteurs
        $acteurs = [];
        for ($i = 1; $i <=10; ++$i)
        {
            $acteur = new Acteur();
            $acteur->setNom($faker->lastName);
            $acteur->setPrenom($faker->firstName);
            $acteur->addFilm($faker->randomElement($films));
            $manager->persist($acteur);
            $acteurs[] = $acteur;
        }

        //Créer les Réalisateur
        $realisateurs = [];
        for ($i = 1; $i <=10; ++$i)
        {
            $realisateur = new Realisateur();
            $realisateur->setNom($faker->lastName);
            $realisateur->setPrenom($faker->firstName);
            $realisateur->addFilm($faker->randomElement($films));
            $manager->persist($realisateur);
            $realisateurs[] = $realisateur;
        }

        //Créer les Scénariste
        $scenaristes = [];
        for ($i = 1; $i <=10; ++$i)
        {
            $scenariste = new Scenariste();
            $scenariste->setNom($faker->lastName);
            $scenariste->setPrenom($faker->firstName);
            $scenariste->addFilm($faker->randomElement($films));
            $manager->persist($scenariste);
            $scenaristes[] = $scenariste;
        }

        //Créer les Note
        $scores = ['1', '2', '3', '4', '5'];
        $notes = [];
        foreach ($scores as $score)
        {
            $note = new Note();
            $note->setScore($score);
            $note->setIdFilm($faker->randomElement($films));
            $note->setIdUtilisateur($faker->randomElement($utilisateurs));
            $manager->persist($note);
            $notes[] = $note;
        }

        //Créer Commentaire
        $commentaires= [];
        for ($i = 1; $i <=30; ++$i)
        {
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->text);
            $commentaire->setIdFilm($faker->randomElement($films));
            $commentaire->setIdUtilisateur($faker->randomElement($utilisateurs));
            $manager->persist($commentaire);
            $commentaires[] = $commentaire;
        }
        
        //Créer Critique
        $critiques = [];
        for ($i = 1; $i <=15; ++$i)
        {
            $critique = new Critique();
            $critique->setTitre('critique '.$i);
            $critique->setContenu($faker->text);
            $critique->setIdFilm($faker->randomElement($films));
            $critique->setIdUtilisateur($faker->randomElement($utilisateurs));
            $critique->setSlug($this->slugger->slug($critique->getTitre())->lower());
            $manager->persist($critique);
            $critiques[] = $critique;
        }

        //Status
        $status = [true, false];
        for ($i = 1; $i <=15; ++$i)
        {
            $statu = new Status();
            $statu->setAVue($status[random_int(0,1)]);
            $statu->setVeutVoir($status[random_int(0,1)]);
            $statu->setIdFilm($faker->randomElement($films));
            $statu->setIdUtilisateur($faker->randomElement($utilisateurs));
            $manager->persist($statu);
            $status[] = $statu;
        }
    
    
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
