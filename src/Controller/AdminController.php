<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Critique;
use App\Entity\Film;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    
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
     * @Route("/admin/commentslist", name="admin_commentslist")
     */
    public function commentairesList()
    {
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('admin/commentsList.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/critiqueslist", name="admin_critiqueslist")
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

    

    /**
     * @Route("/admin/critiqueslist/cdct/{id}", name="admin_confirmcritdelete")
     */
    public function critConfirmSuppr($id)
    {
       $critique = $this->getDoctrine()
            ->getRepository(Critique::class)
            ->find($id);
    
        return $this->render('admin/suppression/deleteCritique.html.twig', [
            'critique' => $critique,
        ]);
    }

    /**
     * @Route("/admin/commentslist/cdcm/{id}", name="admin_confirmcommentsdelete")
     */
    public function commentConfirmSuppr($id)
    {
       $comment = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->find($id);
    
        return $this->render('admin/suppression/deleteComments.html.twig', [
            'comment' => $comment,
        ]);
    }


    

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

    /**
     * @Route("/admin/commentslist/cdu/{id}/delete", name="comment_delete")
    */
    public function commentDelete(Commentaire $comment)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('admin_commentslist');
    }

    /**
     * @Route("/admin/critiqueslist/cdct/{id}/delete", name="critique_delete")
    */
    public function critiqueDelete(Critique $critique)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($critique);
        $entityManager->flush();

        return $this->redirectToRoute('admin_critiqueslist');
    }


    
}






    