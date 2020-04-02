<?php

namespace App\Controller;

use App\Entity\Critique;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CritiqueController extends AbstractController
{

    //Partie Admin

    // Affichage Liste des Critiques

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

    //Page de Confirmation de la Suppression des Critiques

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

    //Suppression des Critiques

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