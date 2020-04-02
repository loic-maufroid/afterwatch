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






    