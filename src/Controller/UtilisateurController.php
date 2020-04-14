<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\CritiqueRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{

     // Afficher la page de Profil et Modifier donnée

    /**
     * @Route("/{username}/profile", name="profil_page")
    */
    public function profilPage($username, Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');
        
        $utilisateur = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findOneBy(["username" => $username]);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->remove('password');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $flashbag = $this->get('session')->getFlashBag();

            $manager = $this->getDoctrine()->getManager();

            $image = $form->get('image')->getData();

            if ($image !== null) {
                // Je récupère le dossier où j'upload mes images
                $uploadDir = __DIR__.'/../../public/img/avatar/';

                if ($utilisateur->getAvatar())
                unlink($uploadDir.$utilisateur->getAvatar());
                // Je fais l'upload en générant un nom pour l'image comme aerf1234.jpg
                $fileName = uniqid().'.'.$image->guessExtension();
                $image->move($uploadDir, $fileName);
                dump($uploadDir);
                // Je mets à jour l'entité pour la BDD
                $utilisateur->setAvatar($fileName);
                
                
               $this->addFlash('uploaded','Avatar changé !');
            }
            
            $oldpw = htmlspecialchars(trim($form->get('oldpassword')->getData()));
            $pw = htmlspecialchars(trim($form->get('newpassword')->getData()));
            $confirmpw = htmlspecialchars(trim($form->get('confirmpassword')->getData()));

            if ($oldpw && $pw && $confirmpw && $oldpw != "" && $pw != "" && $confirmpw != ""){
                if ($passwordEncoder->isPasswordValid($utilisateur,$oldpw)){
                    if ($pw == $confirmpw && strlen($pw) < 4096){
                    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,$pw));
                    $this->addFlash('changepw',"Mot de passe bien changé");
                    }
                    else
                    $this->addFlash('pwerror',"Confirmation de nouveau mot de passe erronnée");
                }
                else
                $this->addFlash("pwerror","Le mot de passe entré pour l'ancien mot de passe ne correspond pas à ce dernier.");
            }

            $form = $this->createForm(UtilisateurType::class, $utilisateur);

            $form->remove('password');

            $manager->flush();

        }

        dump($form);

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