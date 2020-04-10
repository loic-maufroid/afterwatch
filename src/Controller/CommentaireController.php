<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\CritiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    //Partie Commune

    //Partie Admin

    //Page de Confirmation de la Suppression des Commentaires

     /**
     * @Route("/admin/commentslist/cdcm/{id}", name="admin_confirmcommentsdelete")
     */
    public function commentConfirmSuppr($id,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $comment = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->find($id);
    
        $notification =  $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('admin/suppression/deleteComments.html.twig', [
            'comment' => $comment,
            'notification' => $notification
        ]);
    }

    //Suppression des Commentaires

    /**
     * @Route("/admin/commentslist/cdu/{id}/delete", name="comment_delete")
    */
    public function commentDelete(Commentaire $comment)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('admin_commentslist',['page' => 1]);
    }

    // Modification

    /**
     * @Route("/admin/commentslist/modifiercommentaire/{id}", name="comment_modifier")
    */
    public function commentFormModif($id, Request $request, Commentaire $comment, CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
    
        $notification =  $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('formulaire/formCommentaire.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            'notification' => $notification
        ]);
    }

     // Affichage Liste des Commentaires

    /**
     * @Route("/admin/commentslist/{page}", name="admin_commentslist",   requirements={"page"="[1-9]+"})
     */
    public function commentairesList($page,CommentaireRepository $commentaireRepository, CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $comments = $commentaireRepository->findCommentairePaginator($page);
        $maxPage = ceil(count($comments)/25);

        $notification =  $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('admin/commentsList.html.twig', [
            'comments' => $comments,
            'current_page' => $page,
            'max_page' => $maxPage,
            'notification' => $notification
        ]);
    }

}
    