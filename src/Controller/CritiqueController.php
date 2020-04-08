<?php

namespace App\Controller;

use App\Entity\Critique;
use App\Form\CritiqueType;
use App\Repository\CritiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CritiqueController extends AbstractController
{

    //Partie Admin

    //Page de Confirmation de la Suppression des Critiques

    /**
     * @Route("/admin/critiqueslist/cdct/{id}", name="admin_confirmcritdelete")
     */
    public function critConfirmSuppr($id,CritiqueRepository $critiqueRepository)
    {
       $critique = $this->getDoctrine()
            ->getRepository(Critique::class)
            ->find($id);
    
        $notification =  $critiqueRepository->findCountSubmittedCritiques();

        return $this->render('admin/suppression/deleteCritique.html.twig', [
            'critique' => $critique,
            'notification' => $notification
        ]);
    }

    //Suppression des Critiques

    /**
     * @Route("/admin/critiqueslist/cdct/{id}/delete", name="critique_delete")
    */
    public function critiqueDelete(Critique $critique)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($critique);
        $entityManager->flush();

        return $this->redirectToRoute('admin_critiqueslist',['page' => 1]);
    }

    //Modification

    /**
     * @Route("/admin/critiqueslist/modifiercritique/{id}", name="critique_modifier")
    */
    public function commentFormModif($id, Request $request, Critique $critique, CritiqueRepository $critiqueRepository)
    {
        $form = $this->createForm(CritiqueType::class, $critique);
        $form->handleRequest($request);

        $review = $this->getDoctrine()
            ->getRepository(Critique::class)
            ->find($id);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('admin_critiqueslist',['page' => 1]);
        }
    
        $notification =  $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('admin/formulaire/formCritique.html.twig', [
            'form' => $form->createView(),
            'review' => $review,
            'notification' => $notification
        ]);
    }

    // Affichage Liste des Critiques

    /**
     * @Route("/admin/critiqueslist/{page}", name="admin_critiqueslist",  requirements={"page"="[1-9]+"})
     */
    public function critiquesList($page,CritiqueRepository $critiqueRepository)
    {
        $critiques = $critiqueRepository->findCritiquePaginator($page);
        $maxPage = ceil(count($critiques)/10);
        /*$tab = [];
        foreach($critiques as $critique){
        $tab[] = [$critique->getIdFilm()->getId(),$critique->getIdUtilisateur()->getId()];
        }*/
        
        $notification =  $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('admin/critiquesList.html.twig', [
            'critiques' => $critiques,
            'current_page' => $page,
            'max_page' => $maxPage,
            'notification' => $notification
        ]);
    }

    //Affichage des critique d'un film
    
}