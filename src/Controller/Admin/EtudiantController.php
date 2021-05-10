<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/admin/etudiants", name="admin_etudiant_index")
     */
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        $etudiants = $etudiantRepository->findAll();
        return $this->render('admin/etudiant/index.html.twig', compact("etudiants"));
    }

    /**
     * @Route("/admin/etudiants/{id<[0-9]+>}/supprimer", name="admin_etudiant_supprimer")
     */
    public function supprimer(Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $em->remove($etudiant);
        $em->flush();

        $this->flashy->success("L'étudiant a bien été supprimée !");
        
        return $this->redirectToRoute('admin_etudiant_index');
    }
}
