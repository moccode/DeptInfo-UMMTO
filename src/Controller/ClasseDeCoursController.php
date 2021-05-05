<?php

namespace App\Controller;

use App\Entity\ClasseDeCours;
use App\Form\ClasseDeCoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseDeCoursController extends AbstractController
{
    /**
     * @Route("/classes", name="app_classedecours_index")
     */
    public function index(): Response
    {
        return $this->render('classe_de_cours/index.html.twig');
    }

    /**
     * @Route("/classes/creer", name="app_classedecours_creer")
     */
    public function creer(Request $request, EntityManagerInterface $em): Response
    {
        $classeDeCours = new ClasseDeCours();
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classeDeCours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('classe_de_cours/creer.html.twig', [
            "formClasseDeCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/editer/{id<[0-9]+>}", name="app_classedecours_editer")
     */
    public function editer(ClasseDeCours $classeDeCours, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classeDeCours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('classe_de_cours/editer.html.twig', [
            "classeDeCours" => $classeDeCours,
            "formClasseDeCours" => $form->createView()
        ]);
    }
}
