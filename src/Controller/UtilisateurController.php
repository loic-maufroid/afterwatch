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

    //Page de Confirmation de la Suppression des Utilisateur

    /**
     * @Route("/admin/users/cdu/{id}", name="admin_confirmuserdelete")
     */
    public function userConfirmSuppr($id)
    {
       $user = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($id);
    
        return $this->render('admin/suppression/deleteUser.html.twig', [
            'user' => $user,
        ]);
    }

    //Suppression des Utilisateur

    /**
     * @Route("/admin/users/cdu/{id}/delete", name="user_delete")
    */
    public function userDelete(Utilisateur $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_userlist');
    }

   

    
}