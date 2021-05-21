<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("", name="app_profil_index")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    /**
     * @Route("/photodeprofil", name="app_profil_photodeprofil", methods={"GET","PUT"})
     */
    public function modifierPhotoDeProfil(Request $request, EntityManagerInterface $em): Response
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

        return $this->render('profil/modifierPhotoDeProfil.html.twig', [
            'formPhotoDeProfil' => $form->createView()
        ]);
    }

    /**
     * @Route("/nometprenom", name="app_profil_nometprenom", methods={"GET","PUT"})
     */
    public function modifierNomEtPrenom(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();

        $form = $this->createFormBuilder($user, [
            'method' => 'PUT'
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom'
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom'
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->flashy->success('Vos nom et prénom ont été mis à jour.');

            return $this->redirectToRoute("app_profil_index");
        }

        return $this->render('profil/modifierNomEtPrenom.html.twig', [
            'formNomEtPrenom' => $form->createView()
        ]);
    }

    /**
     * @Route("/promotion", name="app_profil_promotion", methods={"GET","PUT"})
     */
    public function modifierPromotion(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();

        $form = $this->createFormBuilder($user, [
            'method' => 'PUT'
        ])
        ->add('promotion', EntityType::class, [
            'class' => Promotion::class,
            'choice_label' => 'titre'
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->flashy->success('Votre promotion a été mise à jour.');

            return $this->redirectToRoute("app_profil_index");
        }

        return $this->render('profil/modifierPromotion.html.twig', [
            'formPromotion' => $form->createView()
        ]);
    }

     /**
     * @Route("/email", name="app_profil_email", methods={"GET","PUT"})
     */
    public function modifierEmail(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();

        $form = $this->createFormBuilder($user, [
            'method' => 'PUT'
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email'
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->flashy->success("Votre adresse e-mail a été mise à jour.");

            return $this->redirectToRoute("app_profil_index");
        }

        return $this->render('profil/modifierEmail.html.twig', [
            'formEmail' => $form->createView()
        ]);
    }

    /**
     * @Route("/motdepasse", name="app_profil_motdepasse", methods={"GET","PUT"})
     */
    public function modifierMotDePasse(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, null , [
            'method' => 'PUT',
            'current_password_is_required' => true
        ]);

        $form1 = $this->createForm(ChangePasswordFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));

            $em->persist($user);
            $em->flush();

            $this->flashy->success("Votre mot de passe a été mis à jour.");

            return $this->redirectToRoute("app_profil_index");
        }

        return $this->render('profil/modifierMotDePasse.html.twig', [
            'formMotDePasse' => $form->createView()
        ]);
    }
}
