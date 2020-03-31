<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Realisateur;
use App\Entity\Scenariste;
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

        



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
