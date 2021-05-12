<?php

namespace App\Controller\Admin;

use App\Entity\ClasseDeCours;
use App\Repository\ClasseDeCoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseDeCoursController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/admin/classes", name="admin_classedecours_index", methods={"GET"})
     */
    public function index(ClasseDeCoursRepository $classeDeCoursRepository): Response
    {
        $classesDeCours = $classeDeCoursRepository->findAll();
        return $this->render('admin/classe_de_cours/index.html.twig', compact("classesDeCours"));
    }

    /**
     * @Route("/admin/classes/{id<[0-9]+>}/supprimer", name="admin_classedecours_supprimer", methods={"DELETE"})
     */
    public function supprimer(ClasseDeCours $classeDeCours, EntityManagerInterface $em): Response
    {
        $em->remove($classeDeCours);
        $em->flush();

        $this->flashy->success('La classe de cours a bien été supprimée !');

        return $this->redirectToRoute('admin_classedecours_index');
    }


}