<?php

namespace App\Controller;

use App\Entity\Commentaire;
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
     * @Route("/admin/users", name="admin_userList")
     */
    public function userList()
    {
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('admin/userList.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/commentsList", name="admin_commentsList")
     */
    public function commentairesList()
    {
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('admin/commentsList.html.twig', [
            'comments' => $comments,
        ]);
        
    }
}




    