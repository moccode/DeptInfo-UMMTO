<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/admin/promotions", name="admin_promotion_index", methods={"GET"})
     */
    public function index(PromotionRepository $promotionRepository): Response
    {
        $promotions = $promotionRepository->findAll();
        return $this->render('admin/promotion/index.html.twig', compact("promotions"));
    }

    /**
     * @Route("/admin/promotions/creer", name="admin_promotion_creer", methods={"GET","POST"})
     */
    public function creer(Request $request, EntityManagerInterface $em): Response
    {
        $promotion = new Promotion;

        $form = $this->createForm(PromotionType::class, $promotion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($promotion);
            $em->flush();

            $this->flashy->success('La promotion a bien été crée !');

            return $this->redirectToRoute('admin_promotion_index');
        }

        return $this->render('admin/promotion/creer.html.twig', [
            'formPromotion' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/promotions/{id<[0-9]+>}/editer", name="admin_promotion_editer")
     */
    public function modifier(Promotion $promotion, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($promotion);
            $em->flush();

            $this->flashy->success('La promotion a été mise à jour !');

            return $this->redirectToRoute('admin_promotion_index');
        }

        return $this->render('admin/promotion/editer.html.twig', [
            'promotion' => $promotion,
            'formPromotion' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/promotions/{id<[0-9]+>}/supprimer", name="admin_promotion_supprimer")
     */
    public function supprimer(Promotion $promotion, EntityManagerInterface $em): Response
    {
        $em->remove($promotion);
        $em->flush();

        $this->flashy->success('La promotion a bien été supprimée !');

        return $this->redirectToRoute('admin_promotion_index');
    }
}
