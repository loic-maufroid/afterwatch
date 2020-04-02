<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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


}
