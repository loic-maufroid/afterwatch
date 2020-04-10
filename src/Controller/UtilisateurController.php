<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\CritiqueRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{

     // Afficher la page de Profil et Modifier donnée

    /**
     * @Route("/{username}/profile", name="profil_page")
    */
    public function profilPage($username, Request $request)
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        
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
            'utilisateur' => $utilisateur
        ]);
    }


    //Partie Admin

    //Bannissement des Utilisateur

    /**
     * @Route("/admin/users/cdu/{id}/toggleban", name="user_toggleban")
    */
    public function userToggleBan(Utilisateur $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $user->setBan(!($user->getBan()));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success',$user->getBan() ? $user->getUsername()."(".$user->getEmail().") a bien été dé-banni" : $user->getUsername()."(".$user->getEmail().") a bien été banni");

        return $this->redirectToRoute('admin_userlist',["page" => 1]);
    }

     // Affichage Liste des Utilisateur
   
    /**
     * @Route("/admin/users/{page}", name="admin_userlist",  requirements={"page"="[1-9]+"})
     */
    public function userList($page,UtilisateurRepository $utilisateurRepository,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $utilisateurRepository->findUserPaginator($page);
        $maxPage = ceil(count($users)/25);
        $notification = $critiqueRepository->findCountSubmittedCritiques();

        return $this->render('admin/userList.html.twig', [
            'users' => $users,
            'current_page' => $page,
            'max_page' => $maxPage,
            'notification' => $notification
        ]);
    }
}