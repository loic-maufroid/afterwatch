<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;

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

     // Affichage Liste des Utilisateur
   
    /**
     * @Route("/admin/users/{page}", name="admin_userlist",  requirements={"page"="[1-9]+"})
     */
    public function userList($page)
    {
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findUserPaginator($page);
        $maxPage = ceil(count($users)/25);

        return $this->render('admin/userList.html.twig', [
            'users' => $users,
            'current_page' => $page,
            'max_page' => $maxPage
        ]);
    }

    
}