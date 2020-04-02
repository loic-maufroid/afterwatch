<?php

namespace App\Controller;

use App\Repository\FilmRepository;
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

        $filmsAffiche = $filmRepository->findSixRandomAlafficheFilms();

        return $this->render('welcome/index.html.twig', [
            "filmsCarr" => $filmsCarr,
            "filmsAffiche" => $filmsAffiche
        ]);
    }

    /**
     * @Route("/search",name="search")
     */
    public function search(Request $request,FilmRepository $filmRepository){

        dump($request);

        $query = $request->query->get('searchFilms');

        $films = $filmRepository->findByTitle($query);

        dump($films);

        $idsDir = $filmRepository->findIdsByDirector($query);
        $idsAct = $filmRepository->findIdsByActor($query);

        $idsTemp = array_merge($idsDir,$idsAct);

        $ids = [];
        foreach ($idsTemp as $idTemp){
            $ids [] = $idTemp["id"];
        }

        $ids = array_unique($ids);

        foreach ($ids as $id) {            
            $films[] = $filmRepository->find($id);
        }


        dump($films);

        return $this->render('welcome/search.html.twig',[
            "query" => $query,
            "films" => $films
        ]);
    }

    /**
     * @Route("/autocomplete",name="autocomplete_film_search")
     */
    public function autocomplete(Request $request,FilmRepository $filmRepository){

        if ($request->isXmlHttpRequest()){
            $query = $request->query->get('q');
            $films = $filmRepository->findByTitle($query);


            $idsDir = $filmRepository->findIdsByDirector($query);
            $idsAct = $filmRepository->findIdsByActor($query);

            $idsTemp = array_merge($idsDir,$idsAct);

            $ids = [];
            foreach ($idsTemp as $idTemp){
                $ids [] = $idTemp["id"];
            }

            $ids = array_unique($ids);

            foreach ($ids as $id) {            
                $films[] = $filmRepository->find($id);
            }

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
