<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Repository\FilmRepository;
use App\Form\FilmType;
use App\Repository\GenreRepository;
use App\Repository\RealisateurRepository;
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
    public function addFilm(Request $request, SluggerInterface $slugger,GenreRepository $genreRepository,RealisateurRepository $realisateurRepository)
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

       /* $directors = explode("+",$request->request->all()["film"]["real"]);

        foreach ($directors as $director) {
            $temp = $realisateurRepository->findOneBy(["nom" => $director]);
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
        }*/


    }

        


        dump($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            dump($form);
            dump($film);
            $film->setSlug($slugger->slug($film->getTitre())->lower());


            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($film);

            $entityManager->flush();
        }

        return $this->render('admin/formulaire/addFilm.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
