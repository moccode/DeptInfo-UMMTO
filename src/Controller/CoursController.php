<?php

namespace App\Controller;

use App\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/classes/{id<[0-9]+>}/cours/creer", name="app_cours_creer")
     */
    public function creer(): Response
    {
        $cours = new Cours();

        return $this->render('cours/creer.html.twig');
    }
}
