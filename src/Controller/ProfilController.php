<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/profil", name="app_profil_index")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    /**
     * @Route("/profil/photodeprofil", name="app_profil_photodeprofil",methods={"GET","PUT"})
     */
    public function changerPhotoDeProfil(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();

        $form = $this->createFormBuilder($user, [
            'method' => 'PUT'
        ])
        ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPEG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer ?',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_medium'
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->flashy->success('Le photo de profil a été mise à jour !');

            return $this->redirectToRoute("app_profil_index");
        }

        return $this->render('profil/changerPhotoDeProfil.html.twig', [
            'formPhotoDeProfil' => $form->createView()
        ]);
    }
}
