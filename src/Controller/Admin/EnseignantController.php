<?php

namespace App\Controller\Admin;

use App\Entity\Enseignant;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnseignantController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/admin/enseignants", name="admin_enseignant_index")
     */
    public function index(EnseignantRepository $enseignantRepository): Response
    {
        $enseignants = $enseignantRepository->findAll();
        return $this->render('admin/enseignant/index.html.twig', compact("enseignants"));
    }

    /**
     * @Route("/admin/enseignants/{id<[0-9]+>}/supprimer", name="admin_enseignant_supprimer")
     */
    public function supprimer(Enseignant $enseignant, EntityManagerInterface $em): Response
    {
        $em->remove($enseignant);
        $em->flush();

        $this->flashy->success("L'enseignant a bien été supprimée !");
        
        return $this->redirectToRoute('admin_enseignant_index');
    }
}
