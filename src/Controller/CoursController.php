<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\ClasseDeCoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/creer", name="app_cours_creer")
     */
    public function creer(Request $request, EntityManagerInterface $em, int $id_classedecours, ClasseDeCoursRepository $classeDeCoursRepository): Response
    {

        $cours = new Cours();

        $form = $this->createForm(CoursType::class, $cours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classedecours = $classeDeCoursRepository->findOneBy(['id' => $id_classedecours]);
            $cours->setClasseDeCours($classedecours);
            $em->persist($cours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('cours/creer.html.twig', [
            "formCours" => $form->createView()
        ]);
    }
}
