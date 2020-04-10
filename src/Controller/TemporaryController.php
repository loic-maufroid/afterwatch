<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TemporaryController extends AbstractController{


    /**
     * @Route("/a-propos", name="a_propos")
     */
    public function aPropos()
    {
        return $this->render('welcome/toBuild.html.twig',[]);
    }

    /**
     * @Route("/condition-utilisation", name="condition")
     */
    public function conditionUtilisation()
    {
        return $this->render('welcome/toBuild.html.twig',[]);
    }

    /**
     * @Route("/forum", name="vers_forum")
     */
    public function forum()
    {
        return $this->render('welcome/toBuild.html.twig',[]);
    }

    /**
     * @Route("/contactus", name="contacter_admin")
     */
    public function mailAdmin()
    {
        return $this->render('welcome/toBuild.html.twig',[]);
    }
}



