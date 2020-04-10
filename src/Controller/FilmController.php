<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Commentaire;
use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Realisateur;
use App\Entity\Scenariste;
use App\Entity\Status;
use App\Form\CommentaireType;
use App\Repository\FilmRepository;
use App\Form\FilmType;
use App\Repository\ActeurRepository;
use App\Repository\CritiqueRepository;
use App\Repository\GenreRepository;
use App\Repository\RealisateurRepository;
use App\Repository\ScenaristeRepository;
use App\Repository\StatusRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;

class FilmController extends AbstractController
{

    /**
     * @Route("/film/{slug}", name="details_film")
     */
    public function voir($slug,FilmRepository $filmRepository,Request $request,StatusRepository $statusRepository)
    {

        $film = $filmRepository->findOneBy(["slug" => $slug]);
        $user = $this->getUser();
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();

            $film->addCommentaire($commentaire);
            $user->addCommentaire($commentaire);

            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('details_film', ['slug' => $slug]);
        }
        
        if ($user){
        $status = $statusRepository->findByDoubleId($user->getId(),$film->getId());

        if ($status){
            if ($status->getVeutVoir())
            $boutonVeutVoirEnabled = false;
            else
            $boutonVeutVoirEnabled = true;
            if ($status->getAVue()){
            $boutonAVuEnabled = false;
            $boutonVeutVoirEnabled = false;
            }
            else
            $boutonAVuEnabled = true;
        }
        else{
            $boutonVeutVoirEnabled = true;
            $boutonAVuEnabled = true;
        }
    }
    else{
        $boutonAVuEnabled = null;
        $boutonVeutVoirEnabled = null;
    }

        return $this->render('film/voir.html.twig',[
            "film" => $film,
            'form' => $form->createView(),
            "enabledVeutVoir" => $boutonVeutVoirEnabled,
            "enabledAVu" => $boutonAVuEnabled
        ]);
    }

     /**
     * @Route("/{username}/myFilms/ajouter-a-voir/{slug}", name="ajouter_aVoir")
     */
    public function addToSee($username,$slug,FilmRepository $filmRepository,UtilisateurRepository $utilisateurRepository,StatusRepository $statusRepository){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');

        $film = $filmRepository->findOneBy(["slug" => $slug]);

        $user = $utilisateurRepository->findOneBy(["username" => $username]);

        $status = $statusRepository->findByDoubleId($user->getId(),$film->getId());

        $manager = $this->getDoctrine()->getManager();

        if ($status){
            $status->setVeutVoir(true);
            
           $manager->persist($status);
           $manager->flush();
        
        }
        else{
            $status = new Status();
            $status->setVeutVoir(true);
            $status->setAVue(false);
            $user->addStatus($status);
            $film->addStatuesFilm($status);
            $manager->persist($status);
            $manager->flush();
        }

        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->get("new");
        $flashbag->add("new",$film->getId());

        return $this->redirectToRoute('films_utilisateur',['username' => $username]);


    }

     /**
     * @Route("/{username}/myFilms/ajouter-a-vu/{slug}", name="ajouter_aVu")
     */
    public function addHasSeen($username,$slug,FilmRepository $filmRepository,UtilisateurRepository $utilisateurRepository,StatusRepository $statusRepository){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');

        $film = $filmRepository->findOneBy(["slug" => $slug]);

        $user = $utilisateurRepository->findOneBy(["username" => $username]);

        $status = $statusRepository->findByDoubleId($user->getId(),$film->getId());

        $manager = $this->getDoctrine()->getManager();

        if ($status){
            $status->setAVue(true);
            $status->setVeutVoir(false);
            
           $manager->persist($status);
           $manager->flush();
        
        }
        else{
            $status = new Status();
            $status->setVeutVoir(false);
            $status->setAVue(true);
            $user->addStatus($status);
            $film->addStatuesFilm($status);
            $manager->persist($status);
            $manager->flush();
        }

        $this->get('session')->getFlashBag()->clear();

        return $this->redirectToRoute('films_utilisateur',['username' => $username]);


    }


    /**
     * @Route("/{username}/myFilms/supprimer/{slug}",name="supprimer_filmfromcollection")
     */
    public function deleteFromCollection($username,$slug,FilmRepository $filmRepository,UtilisateurRepository $utilisateurRepository,StatusRepository $statusRepository){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');

        $film = $filmRepository->findOneBy(["slug" => $slug]);

        $user = $utilisateurRepository->findOneBy(["username" => $username]);

        $status = $statusRepository->findByDoubleId($user->getId(),$film->getId());

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($status);
        $manager->flush();

        $this->get('session')->getFlashBag()->clear();

        return $this->redirectToRoute('films_utilisateur',['username' => $username]);
    }



    /**
     * @Route("/{username}/myFilms",name="films_utilisateur")
     */
    public function showMyFilms($username,UtilisateurRepository $utilisateurRepository,FilmRepository $filmRepository){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getUsername() !== $username)
        return $this->redirectToRoute('welcome');
        if (!$this->getUser()->getBan())
        return $this->redirectToRoute('app_logout');

        $user = $utilisateurRepository->findOneBy(["username" => $username]);

        $filmsCarrTemp = [];
        $filmsCarr = [];
        $tabIdsAVu = [];
        $filmsAVu = [];
        $tabIdsVeutVoir = [];
        $filmsVeutVoir = [];

        $temp = $user->getStatuses();
        foreach($temp as $t){
            if ($t->getAVue()){
            $tabIdsAVu []= $t->getIdFilm();
            }
            if ($t->getVeutVoir()){
            $tabIdsVeutVoir []= $t->getIdFilm();
            $filmsCarrTemp []= $t->getIdFilm();
            }
        }
        shuffle($filmsCarrTemp);
        if(count($filmsCarrTemp)<7){
            for ($i=0; $i < count($filmsCarrTemp); $i++) { 
                $filmsCarr []= $filmRepository->find($filmsCarrTemp[$i]);
            }
        }
        else{
            for ($i=0; $i < 7; $i++) { 
                $filmsCarr []= $filmRepository->find($filmsCarrTemp[$i]);
            }
        }

       foreach ($tabIdsAVu as $idAVu) {
           $filmsAVu []= $filmRepository->find($idAVu);
       }

       foreach ($tabIdsVeutVoir as $idVeutVoir){
           $filmsVeutVoir []= $filmRepository->find($idVeutVoir);
       }


        return $this->render('film/myfilms.html.twig',[
            "user" => $user,
            "filmsCarr" => $filmsCarr,
            "filmsAVu" => $filmsAVu,
            "filmsVeutVoir" => $filmsVeutVoir
        ]);

    }

    //Partie Admin


    //Page de Confirmation de la Suppression des Films

    /**
     * @Route("/admin/cdf/{id}", name="admin_confirmfilmdelete")
     */
    public function filmConfirmSuppr($id,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);


        $notification = $critiqueRepository->findCountSubmittedCritiques();
        return $this->render('admin/suppression/deleteFilm.html.twig', [
            'film' => $film,
            'notification' => $notification
        ]);
    }

    //Suppression des Films

    /**
     * @Route("/admin/cdf/{id}/delete", name="film_delete")
    */
    public function filmDelete(Film $film)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($film);
        $entityManager->flush();

        return $this->redirectToRoute('admin',['page' => 1]);
    }

    //Affichage Formulaire Modification

    /**
     * @Route("/admin/modifierfilm/{id}", name="film_modifier")
    */
    public function filmFormModif($id, Request $request, Film $movie,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(FilmType::class, $movie);
        $form->handleRequest($request);
        
        $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin',['page' => 1]);
        }

        $notification = $critiqueRepository->findCountSubmittedCritiques();

        return $this->render('formulaire/formFilm.html.twig', [
            'form' => $form->createView(),
            'film' => $film,
            'notification' => $notification
        ]);
    }

    //Affichage Formulaire Ajout

     /**
     * @Route("/admin/ajouter", name="addfilm")
     */
    public function addFilm(Request $request, SluggerInterface $slugger,GenreRepository $genreRepository,RealisateurRepository $realisateurRepository,
    ScenaristeRepository $scenaristeRepository,ActeurRepository $acteurRepository,FilmRepository $filmRepository,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $film = new Film();
        
        $form = $this->createForm(FilmType::class, $film);
        
        if (isset($request->request->all()["film"])){
        $genres = explode("+",$request->request->all()["film"]["genre"]);

        foreach ($genres as $genre) {
            $temp = $genreRepository->findOneBy(["type" => $genre]);
            if ($temp)
            $form->getData()->addGenreFilm($temp);
            else {
            $genreTemp = new Genre();
            $genreTemp->setType($genre);
            dump($genreTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($genreTemp);
            $manager->flush();
            dump($genreTemp);
            $form->getData()->addGenreFilm($genreTemp);
            }
        }

        $directors = explode("+",$request->request->all()["film"]["real"]);

        foreach ($directors as $director) {
            $temp = $realisateurRepository->findOneBy(["nom" => $director]);
            if ($temp)
            $form->getData()->addRealise($temp);
            else {
            $dirTemp = new Realisateur();
            $dirTemp->setNom($director);
            dump($dirTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dirTemp);
            $manager->flush();
            dump($dirTemp);
            $form->getData()->addRealise($dirTemp);
            }
        }


        $scenas = explode("+",$request->request->all()["film"]["scen"]);

        foreach ($scenas as $scena) {
            $temp = $scenaristeRepository->findOneBy(["nom" => $scena]);
            if ($temp)
            $form->getData()->addScenario($temp);
            else {
            $scenTemp = new Scenariste();
            $scenTemp->setNom($scena);
            dump($scenTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($scenTemp);
            $manager->flush();
            dump($scenTemp);
            $form->getData()->addScenario($scenTemp);
            }
        }

        $actors = explode("+",$request->request->all()["film"]["act"]);

        foreach ($actors as $actor) {
            $temp = $acteurRepository->findOneBy(["nom" => $actor]);
            if ($temp)
            $form->getData()->addActeurJoue($temp);
            else {
            $actTemp = new Acteur();
            $actTemp->setNom($actor);
            dump($actTemp);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($actTemp);
            $manager->flush();
            dump($actTemp);
            $form->getData()->addActeurJoue($actTemp);
            }
        }

    }

        dump($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            dump($form);
            dump($film);
            $film->setSlug($slugger->slug($film->getTitre())->lower());

            $verif = $filmRepository->findOneBy(["slug" => $film->getSlug()]);
            dump($verif);

            if ($verif){
               $this->addFlash('error',"Film déja existant dans la base de données");
            }
            else{

            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($film);

            $entityManager->flush();

            $this->addFlash('success',"Film bien ajouté !");

            return $this->redirectToRoute('admin',['page' => 1]);
            }
            
        }
        
        $notification = $critiqueRepository->findCountSubmittedCritiques();

        return $this->render('formulaire/addFilm.html.twig', [
            'form' => $form->createView(),
            "notification" => $notification
        ]);
    }

     //Page Index de la Partie Admin(également affichage du tableau Films)

    /**
     * @Route("/admin/{page}", name="admin", requirements={"page"="[1-9]+"})
     */
    public function indexAdmin($page,FilmRepository $filmRepository,CritiqueRepository $critiqueRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $films = $filmRepository->findFilmPaginator($page);
        $maxPage = ceil(count($films)/20);
        $notification = $critiqueRepository->findCountSubmittedCritiques();;

        return $this->render('admin/index.html.twig', [
            'films' => $films,
            'current_page' => $page,
            'max_page' => $maxPage,
            'notification' => $notification
        ]);
    }

}
