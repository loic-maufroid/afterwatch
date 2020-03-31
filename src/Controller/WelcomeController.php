<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index()
    {

        return $this->render('welcome/index.html.twig', []);
    }

    /**
     * @Route("/search",name="search")
     */
    public function search(Request $request,FilmRepository $filmRepository){

        dump($request);

        $query = $request->query->get('searchFilms');

        dump($query);

        $films = $filmRepository->findByTitle($query);

        dump($films);

        $idsDir = $filmRepository->findIdsByDirector($query);
        $idsAct = $filmRepository->findIdsByActor($query);

        dump($idsDir);
        dump($idsAct);

        $idsTemp = array_merge($idsDir,$idsAct);
        dump($idsTemp);

        $ids = [];
        foreach ($idsTemp as $idTemp){
            $ids [] = $idTemp["id"];
        }

        $ids = array_unique($ids);

        dump($ids);
        foreach ($ids as $id) {            
            $films[] = $filmRepository->find($id);
        }


        dump($films);

        return $this->render('welcome/search.html.twig',[
            "query" => $query,
            "films" => $films
        ]);
    }
}
