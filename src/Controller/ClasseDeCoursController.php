<?php

namespace App\Controller;

use App\Entity\ClasseDeCours;
use App\Entity\Enseignant;
use App\Form\ClasseDeCoursType;
use App\Repository\ClasseDeCoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/classes")
 */
class ClasseDeCoursController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("", name="app_classedecours_index", methods={"GET"})
     */
    public function index(ClasseDeCoursRepository $classeDeCoursRepository): Response
    {
        $classesDeCours = $classeDeCoursRepository->findAll();

        return $this->render('classe_de_cours/index.html.twig', compact("classesDeCours"));
    }

    /**
     * @Route("/creer", name="app_classedecours_creer", methods={"GET","POST"})
     * @isGranted("ROLE_ENSEIGNANT", statusCode=401, message="Accès non autorisé")
     */
    public function creer(Request $request, EntityManagerInterface $em): Response
    {
        $classeDeCours = new ClasseDeCours();
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $classeDeCours->setEnseignant($this->getUser());

            $em->persist($classeDeCours);
            $em->flush();

            $this->flashy->success('La classe de cours a bien été créee.');

            return $this->redirectToRoute("app_classedecours_consulter", [
                'id_classedecours' => $classeDeCours->getId()
            ]);
        }

        return $this->render('classe_de_cours/creer.html.twig', [
            "formClasseDeCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id_classedecours<[0-9]+>}", name="app_classedecours_consulter", methods={"GET"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * 
     */
    public function consulter(ClasseDeCours $classeDeCours): Response
    {
        return $this->render('classe_de_cours/consulter.html.twig', compact("classeDeCours"));
    }

    /**
     * @Route("/{id_classedecours<[0-9]+>}/editer", name="app_classedecours_editer", methods={"GET","PUT"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Security("is_granted('ROLE_ENSEIGNANT') and classeDeCours.getEnseignant() == user", statusCode=401, message="Accès non autorisé")
     */
    public function editer(ClasseDeCours $classeDeCours, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($classeDeCours);
            $em->flush();

            $this->flashy->success('Classe de cours mise à jour.');

            return $this->redirectToRoute("app_classedecours_consulter", [
                'id_classedecours' => $classeDeCours->getId()
            ]);
        }

        return $this->render('classe_de_cours/editer.html.twig', [
            "classeDeCours" => $classeDeCours,
            "formClasseDeCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id_classedecours<[0-9]+>}/supprimer", name="app_classedecours_supprimer", methods={"DELETE"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * @Security("is_granted('ROLE_ENSEIGNANT') and classeDeCours.getEnseignant() == user", statusCode=401, message="Accès non autorisé")
     * 
     */
    public function supprimer(ClasseDeCours $classeDeCours, EntityManagerInterface $em): Response
    {
        $em->remove($classeDeCours);
        $em->flush();

        $this->flashy->success('Classe de cours supprimée !');

        return $this->redirectToRoute('app_home');
    }
}
