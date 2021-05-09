<?php

namespace App\Controller;

use App\Entity\ClasseDeCours;
use App\Entity\Cours;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use SplFileInfo;

class CoursController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/creer", name="app_cours_creer", methods={"GET","POST"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Security("is_granted('ROLE_ENSEIGNANT') and classeDeCours.getEnseignant() == user", statusCode=401, message="Accès non autorisé")
     * 
     */
    public function creer(Request $request, EntityManagerInterface $em, ClasseDeCours $classeDeCours): Response
    {
        $cours = new Cours();

        $form = $this->createForm(CoursType::class, $cours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cours->setClasseDeCours($classeDeCours)
                ->setEnseignant($this->getUser());

            $em->persist($cours);
            $em->flush();

            $this->flashy->success('Le cours a bien été crée !');

            return $this->redirectToRoute("app_cours_consulter", [
                'id_classedecours' => $classeDeCours->getId(),
                'id_cours' => $cours->getId()
            ]);
        }

        return $this->render('cours/creer.html.twig', [
            "id_classedecours" => $classeDeCours->getId(),
            "formCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}", name="app_cours_consulter", methods={"GET"})
     * @Entity("cours", expr="repository.find(id_cours)")
     */
    public function consulter(Cours $cours): Response
    {
        /**
         * On récupère l'extension du fichier cours
         */

        $info = new SplFileInfo($cours->getNomFichierCours());

        $extensionFichier = $info->getExtension();

        return $this->render('cours/consulter.html.twig', [
            'cours' => $cours,
            'extensionFichier' => $extensionFichier
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/editer", name="app_cours_editer", methods={"GET","PUT"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Entity("cours", expr="repository.find(id_cours)")
     * @Security("is_granted('ROLE_ENSEIGNANT') and classeDeCours.getEnseignant() == user and cours.getEnseignant() == user", statusCode=401, message="Accès non autorisé")
     * 
     */
    public function editer(Cours $cours, Request $request, EntityManagerInterface $em, ClasseDeCours $classeDeCours): Response
    {
        $form = $this->createForm(CoursType::class, $cours, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cours->setClasseDeCours($classeDeCours);
            $em->persist($cours);
            $em->flush();

            $this->flashy->success('Le cours a été mis à jour !');

            return $this->redirectToRoute("app_cours_consulter", [
                'id_classedecours' => $classeDeCours->getId(),
                'id_cours' => $cours->getId()
            ]);
        }

        return $this->render('cours/editer.html.twig', [
            "cours" => $cours,
            "formCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/cours/{id_cours<[0-9]+>}/supprimer", name="app_cours_supprimer", methods={"DELETE"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Entity("cours", expr="repository.find(id_cours)")
     * @Security("is_granted('ROLE_ENSEIGNANT') and classeDeCours.getEnseignant() == user and cours.getEnseignant() == user", statusCode=401, message="Accès non autorisé")
     */
    public function supprimer(Cours $cours, EntityManagerInterface $em, ClasseDeCours $classeDeCours): Response
    {

        $em->remove($cours);
        $em->flush();

        $this->flashy->success('Le cours a bien été supprimé !');

        return $this->redirectToRoute("app_classedecours_consulter", [
            'id_classedecours' => $classeDeCours->getId()
        ]);
    }
}
