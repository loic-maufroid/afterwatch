<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Realisateur;
use App\Entity\Scenariste;
use App\Repository\FilmRepository;
use App\Form\FilmType;
use App\Repository\ActeurRepository;
use App\Repository\GenreRepository;
use App\Repository\RealisateurRepository;
use App\Repository\ScenaristeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;

class FilmController extends AbstractController
{

    /**
     * @Route("/film/{slug}", name="details_film")
     */
    public function voir($slug,FilmRepository $filmRepository){
        
        $film = $filmRepository->findOneBy(["slug" => $slug]);

        return $this->render('film/voir.html.twig',[
            "film" => $film
        ]);
    }

    

    //Partie Admin

    //Page Index de la Partie Admin(également affichage du tableau Films)

    /**
     * @Route("/admin", name="admin")
     */
    public function indexAdmin()
    {
        $films = $this->getDoctrine()->getRepository(Film::class)->findAllFilms();
        return $this->render('admin/index.html.twig', [
            'films' => $films,
        ]);
    }

    //Page de Confirmation de la Suppression des Films

    /**
     * @Route("/admin/cdf/{id}", name="admin_confirmfilmdelete")
     */
    public function filmConfirmSuppr($id)
    {
       $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);
    
        return $this->render('admin/suppression/deleteFilm.html.twig', [
            'film' => $film,
        ]);
    }

    //Suppression des Films

    /**
     * @Route("/admin/cdf/{id}/delete", name="film_delete")
    */
    public function filmDelete(Film $film)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($film);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    //Affichage Formulaire Modification

    /**
     * @Route("/admin/modifierfilm/{id}", name="film_modifier")
    */
    public function filmFormModif($id)
    {
        $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);

        return $this->render('admin/formulaire/formFilm.html.twig', [
            'film' => $film,
        ]);
    }

    //Affichage Formulaire Ajout

     /**
     * @Route("/admin/ajouter", name="addfilm")
     */
    public function addFilm(Request $request, SluggerInterface $slugger,GenreRepository $genreRepository,RealisateurRepository $realisateurRepository,
    ScenaristeRepository $scenaristeRepository,ActeurRepository $acteurRepository,FilmRepository $filmRepository)
    {
        $film = new Film();
        
        $form = $this->createForm(FilmType::class, $film);
        
        if (isset($request->request->all()["film"])){
        $genres = explode("+",$request->request->all()["film"]["genre"]);

        foreach ($genres as $genre) {
            $temp = $genreRepository->findOneBy(["type" => $genre]);
            if ($temp)
            $form->getData()->addGenreFilm($temp);
            else {
            $genreTemp = new Genre();
            $genreTemp->setType($genre);
            dump($genreTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($genreTemp);
            $manager->flush();
            dump($genreTemp);
            $form->getData()->addGenreFilm($genreTemp);
            }
        }

        $directors = explode("+",$request->request->all()["film"]["real"]);

        foreach ($directors as $director) {
            $temp = $realisateurRepository->findOneBy(["nom" => $director]);
            if ($temp)
            $form->getData()->addRealise($temp);
            else {
            $dirTemp = new Realisateur();
            $dirTemp->setNom($director);
            dump($dirTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dirTemp);
            $manager->flush();
            dump($dirTemp);
            $form->getData()->addRealise($dirTemp);
            }
        }


        $scenas = explode("+",$request->request->all()["film"]["scen"]);

        foreach ($scenas as $scena) {
            $temp = $scenaristeRepository->findOneBy(["nom" => $scena]);
            if ($temp)
            $form->getData()->addScenario($temp);
            else {
            $scenTemp = new Scenariste();
            $scenTemp->setNom($scena);
            dump($scenTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($scenTemp);
            $manager->flush();
            dump($scenTemp);
            $form->getData()->addScenario($scenTemp);
            }
        }

        $actors = explode("+",$request->request->all()["film"]["act"]);

        foreach ($actors as $actor) {
            $temp = $acteurRepository->findOneBy(["nom" => $actor]);
            if ($temp)
            $form->getData()->addActeurJoue($temp);
            else {
            $actTemp = new Acteur();
            $actTemp->setNom($actor);
            dump($actTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($actTemp);
            $manager->flush();
            dump($actTemp);
            $form->getData()->addActeurJoue($actTemp);
            }
        }

    }

        


        dump($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            dump($form);
            dump($film);
            $film->setSlug($slugger->slug($film->getTitre())->lower());

            $verif = $filmRepository->findOneBy(["slug" => $film->getSlug()]);
            dump($verif);

            if ($verif){
               $this->addFlash('error',"Film déja existant dans la base de données");
            }
            else{

            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($film);

            $entityManager->flush();

            $this->addFlash('success',"Film bien ajouté !");
            }
        }

        return $this->render('admin/formulaire/addFilm.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
