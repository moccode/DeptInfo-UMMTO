<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


class CommentaireController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }
    /**
     * @Route("{id_cours<[0-9]+>}/commentaire/{id_commentaire<[0-9]+>}/supprimer", name="app_commentaire_supprimer", methods={"DELETE"})
     * @Entity("cours", expr="repository.find(id_cours)")
     * @Entity("commentaire", expr="repository.find(id_commentaire)")
     */
    public function supprimer(Cours $cours, Commentaire $commentaire, EntityManagerInterface $em): Response
    {

        $em->remove($commentaire);
        $em->flush();

    
        $this->flashy->success('Le commentaire a été supprimé !');

        return $this->redirectToRoute("app_cours_consulter", [
            'id_classedecours' => $cours->getClasseDeCours()->getId(),
            'id_cours' => $cours->getId()
        ]);
    }
}
