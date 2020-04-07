<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{

     // Afficher la page de Profil et Modifier donnée

    /**
     * @Route("/{username}/profil", name="profil_page")
    */
    public function profilPage($username, Request $request)
    {
        
        $utilisateur = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findOneBy(["username" => $username]);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('welcome');
        }

        return $this->render('security/userProfil.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }


    //Partie Admin

    // Affichage Liste des Utilisateur

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

    //Bannissement des Utilisateur

    /**
     * @Route("/admin/users/cdu/{id}/toggleban", name="user_toggleban")
    */
    public function userToggleBan(Utilisateur $user)
    {
        $user->setBan(!($user->getBan()));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success',$user->getBan() ? $user->getUsername()."(".$user->getEmail().") a bien été dé-banni" : $user->getUsername()."(".$user->getEmail().") a bien été banni");

        return $this->redirectToRoute('admin_userlist');
    }

   

    
}