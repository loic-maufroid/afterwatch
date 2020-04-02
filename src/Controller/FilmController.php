<?php

namespace App\Controller;

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
}
