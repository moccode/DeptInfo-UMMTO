<?php

namespace App\Controller;

use App\Entity\ClasseDeCours;
use App\Entity\Commentaire;
use App\Entity\Cours;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CommentaireController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/commentaire/{id_commentaire<[0-9]+>}/editer", name="app_commentaire_editer", methods={"GET","PUT"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Entity("cours", expr="repository.find(id_cours)")
     * @Entity("commentaire", expr="repository.find(id_commentaire)")
     * @Security("commentaire.getUser() == user", statusCode=401, message="Accès non autorisé !") 
     */
    public function editer(Commentaire $commentaire, Request $request, EntityManagerInterface $em, Cours $cours, ClasseDeCours $classeDeCours): Response
    {

        $form = $this->createForm(CommentaireType::class, $commentaire, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($commentaire);
            $em->flush();

            $this->flashy->success('Le commentaire a été mis à jour !');

            return $this->redirectToRoute("app_cours_consulter", [
                'id_classedecours' => $classeDeCours->getId(),
                'id_cours' => $cours->getId()
            ]);
        }

        return $this->render('cours/editerCommentaire.html.twig', [
            "formCommentaire" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/commentaire/{id_commentaire<[0-9]+>}/supprimer", name="app_commentaire_supprimer", methods={"DELETE"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Entity("cours", expr="repository.find(id_cours)")
     * @Entity("commentaire", expr="repository.find(id_commentaire)")
     * @Security("commentaire.getUser() == user", statusCode=401, message="Accès non autorisé !") 
     */
    public function supprimer(Commentaire $commentaire, EntityManagerInterface $em, Cours $cours, ClasseDeCours $classeDeCours): Response
    {

        $em->remove($commentaire);
        $em->flush();

    
        $this->flashy->success('Le commentaire a été supprimé !');

        return $this->redirectToRoute("app_cours_consulter", [
            'id_classedecours' => $classeDeCours->getId(),
            'id_cours' => $cours->getId()
        ]);
    }
}
