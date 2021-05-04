<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseDeCoursController extends AbstractController
{
    /**
     * @Route("/classe", name="app_classedecours_index")
     */
    public function index(): Response
    {
        return $this->render('classe_de_cours/index.html.twig');
    }

    /**
     * @Route("/classe/creer", name="app_classedecours_creer")
     */
    public function creer(): Response
    {
        return $this->render('classe_de_cours/creer.html.twig');
    }
}
