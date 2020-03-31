<?php

namespace App\Controller;

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
    public function search(Request $request){

        

        return $this->render('welcome/search.html.twig',[]);
    }
}
