<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    //Partie Commune

    //Ajouter un commentaire

   
    /*public function addCommentaire(Request $request)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('details_film');
        }

        return $this->render('admin/formulaire/addCommentaire.html.twig', [
            'form' => $form->createView(),
        ]);

    }*/

    //Partie Admin

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

        return $this->redirectToRoute('admin_commentslist',['page' => 1]);
    }

    // Modification

    /**
     * @Route("/admin/commentslist/modifiercommentaire/{id}", name="comment_modifier")
    */
    public function commentFormModif($id, Request $request, Commentaire $comment)
    {
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        $commentaire = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->find($id);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_commentslist',['page' => 1]);
        }
    
        return $this->render('admin/formulaire/formCommentaire.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire,
        ]);
    }

     // Affichage Liste des Commentaires

    /**
     * @Route("/admin/commentslist/{page}", name="admin_commentslist",   requirements={"page"="[1-9]+"})
     */
    public function commentairesList($page,CommentaireRepository $commentaireRepository)
    {
        $comments = $commentaireRepository->findCommentairePaginator($page);
        $maxPage = ceil(count($comments)/25);

        return $this->render('admin/commentsList.html.twig', [
            'comments' => $comments,
            'current_page' => $page,
            'max_page' => $maxPage
        ]);
    }

}
    