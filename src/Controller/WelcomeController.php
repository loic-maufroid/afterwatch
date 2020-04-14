<?php

namespace App\Controller;

use App\Repository\ActeurRepository;
use App\Repository\CritiqueRepository;
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

        $filmsAffiche = $filmRepository->findRandomAlafficheFilms(5);

        $filmsSortie = $filmRepository->findRandomSortieFilms(5);

        $this->get('session')->getFlashBag()->clear();

        return $this->render('welcome/index.html.twig', [
            "filmsCarr" => $filmsCarr,
            "filmsAffiche" => $filmsAffiche,
            "filmsSortie" => $filmsSortie
        ]);
    }

    /**
     * @Route("/a-l-affiche",name="alaffiche")
     */
    public function showAlaffiche(FilmRepository $filmRepository){

        $filmsCarr = $filmRepository->findRandomAlafficheFilms(7);
        $films = $filmRepository->findAllAlafficheFilms();

        $this->get('session')->getFlashBag()->clear();

        return $this->render('welcome/alaffiche.html.twig',[
            "filmsCarr" => $filmsCarr,
            "films" => $films
        ]);

    }

    /**
     * @Route("/next",name="futures_sorties")
     */
    public function showFutures(FilmRepository $filmRepository){

        $filmsCarr = $filmRepository->findRandomSortieFilms(7);
        $films = $filmRepository->findAllSortieFilms();

        $this->get('session')->getFlashBag()->clear();

        return $this->render('welcome/next.html.twig',[
            "filmsCarr" => $filmsCarr,
            "films" => $films
        ]);
    }

    /**
     * @Route("/popular",name="recommandations")
     */
    public function showPopular(CritiqueRepository $critiqueRepository,FilmRepository $filmRepository){

        $tempCarr = $critiqueRepository->findWellReviewedFilmIds(7);
        $filmsCarr = [];
        foreach($tempCarr as $valueTempCarr){
            $filmsCarr []= ["film" => $filmRepository->find($valueTempCarr["idFilm"]),"noteMoy" => $this->roundNote($valueTempCarr["moyenne"])];
        }

        $temp = $critiqueRepository->findWellReviewedFilmIds(30);
        $films = [];
        foreach($temp as $valueTemp){
            $films []= ["film" => $filmRepository->find($valueTemp["idFilm"]),"noteMoy" => $this->roundNote($valueTemp["moyenne"])];
        }

        $this->get('session')->getFlashBag()->clear();

        return $this->render('welcome/popular.html.twig',[
            "filmsCarr" => $filmsCarr,
            "films" => $films
        ]);
    }

    public function roundNote($rawNote){
        return intval(round($rawNote));
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

        $this->get('session')->getFlashBag()->clear();   

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
