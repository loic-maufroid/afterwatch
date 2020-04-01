<?php

namespace App\Controller;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $films = $this->getDoctrine()->getRepository(Film::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'films' => $films,
        ]);
    }
}
