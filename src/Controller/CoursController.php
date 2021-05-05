<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\ClasseDeCoursRepository;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/creer", name="app_cours_creer", methods={"GET","POST"})
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

            return $this->redirectToRoute("app_cours_consulter", [
                'id_classedecours' => $id_classedecours,
                'id_cours' => $cours->getId()
            ]);
        }

        return $this->render('cours/creer.html.twig', [
            "id_classedecours" => $id_classedecours,
            "formCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}", name="app_cours_consulter", methods={"GET"})
     */
    public function consulter(int $id_cours, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findOneBy(['id' => $id_cours]);
        return $this->render('cours/consulter.html.twig', compact("cours"));
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/editer", name="app_cours_editer", methods={"GET","PUT"})
     */
    public function editer(int $id_cours, CoursRepository $coursRepository, Request $request, EntityManagerInterface $em, int $id_classedecours, ClasseDeCoursRepository $classeDeCoursRepository): Response
    {
        $cours = $coursRepository->findOneBy(['id' => $id_cours]);

        $form = $this->createForm(CoursType::class, $cours, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classedecours = $classeDeCoursRepository->findOneBy(['id' => $id_classedecours]);
            $cours->setClasseDeCours($classedecours);
            $em->persist($cours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('cours/editer.html.twig', [
            "cours" => $cours,
            "formCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/delete", name="app_cours_supprimer", methods={"DELETE"})
     */
    public function supprimer(int $id_cours, CoursRepository $coursRepository, EntityManagerInterface $em, int $id_classedecours): Response
    {
        $cours = $coursRepository->findOneBy(['id' => $id_cours]);

        $em->remove($cours);
        $em->flush();

        return $this->redirectToRoute("app_classedecours_consulter", [
            'id_classedecours' => $id_classedecours
        ]);
    }
}
