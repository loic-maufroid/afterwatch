<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{

    //Partie Admin

    // Affichage Liste des Commentaires

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

    //Page de Confirmation de la Suppression des Commentaires

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

    //Suppression des Commentaires

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


}
    