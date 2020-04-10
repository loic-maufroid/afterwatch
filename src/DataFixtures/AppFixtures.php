<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Acteur;
use App\Entity\Commentaire;
use App\Entity\Critique;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Realisateur;
use App\Entity\Scenariste;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private $slugger;
    private $passwordEncoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Créer les Utilisateurs
        $utilisateur = new Utilisateur();
        $utilisateur->setUsername('RolandAdmin');
        $utilisateur->setEmail('administateur.roland@afterwatch.fr');
        $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, 'Roland') );
        $utilisateur->setRoles(['ROLE_ADMIN']);
        $utilisateur->setBan(true);
        $utilisateur->setAvatar('Fruit_basket.jpg');
        $manager->persist($utilisateur);

        $utilisateur = new Utilisateur();
        $utilisateur->setUsername('LoicAdmin');
        $utilisateur->setEmail('administateur.loic@afterwatch.fr');
        $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, 'Loic') );
        $utilisateur->setRoles(['ROLE_ADMIN']);
        $utilisateur->setBan(true);
        $utilisateur->setAvatar('courgette.jpg');
        $manager->persist($utilisateur);

        $utilisateur = new Utilisateur();
        $utilisateur->setUsername('Farn');
        $utilisateur->setEmail('administateur.farn@afterwatch.fr');
        $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, 'Courgette') );
        $utilisateur->setRoles(['ROLE_ADMIN']);
        $utilisateur->setBan(true);
        $utilisateur->setAvatar('iamafern.jpg');
        $manager->persist($utilisateur);

        $utilisateur = new Utilisateur();
        $utilisateur->setUsername('DeathEm');
        $utilisateur->setEmail('administateur.deathem@afterwatch.fr');
        $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, 'DeathEm') );
        $utilisateur->setRoles(['ROLE_ADMIN']);
        $utilisateur->setBan(true);
        $utilisateur->setAvatar('pervyharry.png');
        $manager->persist($utilisateur);

        $utilisateurs = [];
        for ($i = 1; $i <=17; ++$i)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setUsername('utilisateur'.$i);
            $utilisateur->setEmail($faker->email);
            $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, $faker->words($nb = 7, $asText=true) ));
            $utilisateur->setRoles(['ROLE_USER']);
            $utilisateur->setBan(true);
            $manager->persist($utilisateur);
            $utilisateurs[] = $utilisateur;
        }

        //Créer les Films
    
        $films = [];
        for ($i = 1; $i <=10; ++$i)
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
            $acteur->setNom($faker->lastName.' '.$faker->firstName);
            $acteur->addFilm($faker->randomElement($films));
            $manager->persist($acteur);
            $acteurs[] = $acteur;
        }

        //Créer les Réalisateur
        $realisateurs = [];
        for ($i = 1; $i <=10; ++$i)
        {
            $realisateur = new Realisateur();
            $realisateur->setNom($faker->lastName.' '.$faker->firstName);
            $realisateur->addFilm($faker->randomElement($films));
            $manager->persist($realisateur);
            $realisateurs[] = $realisateur;
        }

        //Créer les Scénariste
        $scenaristes = [];
        for ($i = 1; $i <=10; ++$i)
        {
            $scenariste = new Scenariste();
            $scenariste->setNom($faker->lastName.' '.$faker->firstName);
            $scenariste->addFilm($faker->randomElement($films));
            $manager->persist($scenariste);
            $scenaristes[] = $scenariste;
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
            $critique->setNote($faker->numberBetween(1,5));
            $critique->setIdFilm($faker->randomElement($films));
            $critique->setIdUtilisateur($faker->randomElement($utilisateurs));
            $critique->setSlug($this->slugger->slug($critique->getTitre())->lower());
            $critique->setPublication(false);
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
