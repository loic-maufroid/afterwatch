<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Critique;
use App\Entity\Film;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $films = $this->getDoctrine()->getRepository(Film::class)->findAllFilms();
        return $this->render('admin/index.html.twig', [
            'films' => $films,
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_userlist")
     */
    public function userList()
    {
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('admin/userList.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/commentsList", name="admin_commentslist")
     */
    public function commentairesList()
    {
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('admin/commentsList.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/critiquesList", name="admin_critiqueslist")
     */
    public function critiquesList()
    {
        $critiques = $this->getDoctrine()->getRepository(Critique::class)->findAll();
        /*$tab = [];
        foreach($critiques as $critique){
        $tab[] = [$critique->getIdFilm()->getId(),$critique->getIdUtilisateur()->getId()];
        }*/
                
        return $this->render('admin/critiquesList.html.twig', [
            'critiques' => $critiques,
        ]);
    }
}




    