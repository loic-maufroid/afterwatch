<?php

namespace App\Controller;

use App\Entity\Critique;
use App\Form\CritiqueType;
use App\Repository\CritiqueRepository;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CritiqueController extends AbstractController
{
    //partie Commune

     //Affichage des critiques d'un film

    /**
     * @Route("/film/{slug}/critiques", name="critiques")
     */
    public function critiqueView($slug,FilmRepository $filmRepository)
    {
        $film = $filmRepository->findOneBy(["slug" => $slug]);

        return $this->render('film/listeCritique.html.twig', [
            'film' => $film,
        ]);
    }

    
    /**
     * @Route("/film/{slug}/critiques/edit", name="ajout_critique")
     */
    public function addCritique($slug, FilmRepository $filmRepository,Request $request, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');

        $critique = new Critique();
        $film = $filmRepository->findOneBy(["slug" => $slug]);
        $user = $this->getUser();

        $form = $this->createForm(CritiqueType::class, $critique);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $critique->setSlug($slugger->slug($critique->getTitre())->lower())->setPublication(false);
            $user->addCritique($critique);
            $film->addCritique($critique);
        
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($critique);
            $manager->flush(); 

            $this->addFlash('submitted',"Votre critique du film ".$film->getTitre()." a été enregistrée. Sa publication sur le site est maintenant soumise à l'accord d'un administrateur.");
            return $this->redirectToRoute('critiques', ['slug' => $slug]);
        }

        return $this->render('formulaire/addCritique.html.twig', [
            'form' => $form->createView(),
            'film' => $film,
        ]);
    }

    
    //Affichage d'une critique d'un film

    /**
     * @Route("/film/{slug}/critiques/{slug2}", name="critiqueview")
     */
    public function detailCritique($slug,$slug2,FilmRepository $filmRepository, CritiqueRepository $critiqueRepository)
    {
        $film = $filmRepository->findOneBy(["slug" => $slug]);
        $critique = $critiqueRepository->findOneBy(["slug" => $slug2]);

        return $this->render('film/detailCritique.html.twig', [
            'film' => $film,
            'critique' => $critique,
        ]);
    }

    
    //Partie Admin

    //Page de Confirmation de la Suppression des Critiques


    /**
     * @Route("/admin/critiqueslist/cdct/{id}", name="admin_confirmcritdelete")
     */
    public function critConfirmSuppr($id,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($critique);
        $entityManager->flush();

        return $this->redirectToRoute('admin_critiqueslist',['page' => 1]);
    }

    //Modification

    /**
     * @Route("/admin/critiqueslist/modifiercritique/{id}", name="critique_modifier")
    */
    public function critiqueFormModif($id, Request $request, Critique $critique, CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
        return $this->render('formulaire/formCritique.html.twig', [
            'form' => $form->createView(),
            'review' => $review,
            'notification' => $notification
        ]);
    }

    // Affichage Critique soumise aux Admins pour être publiée

    /**
     * @Route("/admin/critiquesList/verifiercritique/{id}", name="critique_verifier")
     */
    public function critiqueVerif($id,CritiqueRepository $critiqueRepository){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $review = $critiqueRepository->find($id);
        $notification =  $critiqueRepository->findCountSubmittedCritiques();

        return $this->render('admin/critiqueVerif.html.twig',[
            'review' => $review,
            'notification' => $notification
        ]);
    }

    // Validation de la publication de la critique par un Admin

    /**
     * @Route("/admin/critiquesList/publierCritique/{id}", name="critique_publier")
     */
    public function critiquePublier($id,CritiqueRepository $critiqueRepository){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $review = $critiqueRepository->find($id);
        $review->setPublication(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($review);
        $entityManager->flush();

        $this->addFlash('success',"La critique '".$review->getTitre()."' par ".$review->getIdUtilisateur()->getUsername()." a bien été publiée");

        return $this->redirectToRoute('admin_critiqueslist',["page" => 1]);
    }

    // Affichage Liste des Critiques

    /**
     * @Route("/admin/critiqueslist/{page}", name="admin_critiqueslist",  requirements={"page"="[1-9]+"})
     */
    public function critiquesList($page,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
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

   
}