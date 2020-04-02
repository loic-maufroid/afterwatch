<?php

namespace App\Controller;

use App\Repository\ActeurRepository;
use App\Repository\FilmRepository;
use App\Repository\RealisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index(FilmRepository $filmRepository)
    {

        $filmsCarr = $filmRepository->findSevenRandomReleasedFilms();

        $filmsAffiche = $filmRepository->findFiveRandomAlafficheFilms();

        return $this->render('welcome/index.html.twig', [
            "filmsCarr" => $filmsCarr,
            "filmsAffiche" => $filmsAffiche
        ]);
    }

    /**
     * @Route("/search",name="search")
     */
    public function search(Request $request,FilmRepository $filmRepository,RealisateurRepository $realisateurRepository,ActeurRepository $acteurRepository){

        dump($request);

        $query = $request->query->get('searchFilms');

        //Récupére les films dont le nom contient l'expression en recherche
        $films = $filmRepository->findByTitle($query);

        dump($films);

        //Récupére les films réalisés par le nom de l'expression en recherche
        $idsDirTemp = $filmRepository->findIdsByDirector($query);
        dump($idsDirTemp);
        $idsDir = [];

        foreach ($idsDirTemp as $idDirTemp){
            $idsDir[] = $idDirTemp["id"];
        }

        dump($idsDir);
        $directors = [];
        for ($i=0; $i<count($idsDir); $i++) {         
            $directors[] = $realisateurRepository->find($idsDir[$i]);
        }

        dump($directors);

        //Récupére les films dans lesquels joue le nom de l'expression en recherche
        $idsActTemp = $filmRepository->findIdsByActor($query);
        $idsAct = [];

        foreach ($idsActTemp as $idActTemp){
            $idsAct [] = $idActTemp["id"];
        }

        $actors = [];
        for ($i=0; $i<count($idsAct); $i++) {            
            $actors[] = $acteurRepository->find($idsAct[$i]);
        }
     

        return $this->render('welcome/search.html.twig',[
            "query" => $query,
            "films" => $films,
            "directors" => $directors,
            "actors" => $actors
        ]);
    }

    /**
     * @Route("/autocomplete",name="autocomplete_film_search")
     */
    public function autocomplete(Request $request,FilmRepository $filmRepository){

        if ($request->isXmlHttpRequest()){
            $query = $request->query->get('q');
            $films = $filmRepository->findByTitle($query);

            $jsonData = array();
            $idx = 0;
            foreach($films as $film) {  
                $temp = array(
                   'titre' => $film->getTitre() 
                );   
                $jsonData[$idx++] = $temp;  
             } 
        }
        else
        return $this->redirectToRoute('welcome');

        return new JsonResponse($jsonData);

    }
}
