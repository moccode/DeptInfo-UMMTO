<?php

namespace App\Controller\Admin;

use App\Repository\ClasseDeCoursRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(PromotionRepository $promotionRepository, EtudiantRepository $etudiantRepository, EnseignantRepository $enseignantRepository, ClasseDeCoursRepository $classeDeCoursRepository): Response
    {

        $promotions = $promotionRepository->findAll();
        $etudiants = $etudiantRepository->findAll();
        $enseignants = $enseignantRepository->findAll();
        $classesDeCours = $classeDeCoursRepository->findAll();

        return $this->render('admin/home/index.html.twig', compact("promotions", "etudiants", "enseignants", "classesDeCours"));
    }
}
