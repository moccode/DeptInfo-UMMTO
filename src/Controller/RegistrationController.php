<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Form\RegistrationEtudiantFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier, FlashyNotifier $flashy)
    {
        $this->emailVerifier = $emailVerifier;

        $this->flashy = $flashy;
    }

    /**
     * @Route("/enseignant/inscription", name="app_registration_enseignant")
     */
    public function inscriptionEnseignant(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em): Response
    {
        // Si un utilisateur est connecté
        if ($this->getUser()) {
            $this->flashy->error("Vous êtes déja connecté !");
            return $this->redirectToRoute('app_home');
        }

        $user = new Admin;

        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // On encode le mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                ->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();

            // Envoi de l'email pour l'activation du compte
            $this->emailVerifier->sendEmailConfirmation('app_verifier_email', $user,
            (new TemplatedEmail())
                ->from(new Address('deptinfo@ummto.com', 'DeptInfo - Département Informatique'))
                ->to($user->getEmail())
                ->subject('Vérification de votre adresse e-mail')
                ->htmlTemplate('emails/registration/confirmation_email.html.twig')
            );

            $this->flashy->success("Un e-mail d'activation a été envoyé à votre adresse e-mail.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/enseignant.html.twig', [
            'formInscription' => $form->createView()
        ]);
    }

    /**
     * @Route("/etudiant/inscription", name="app_registration_etudiant")
     */
    public function inscriptionEtudiant(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em): Response
    {
        // Si un utilisateur est connecté
        if ($this->getUser()) {
            $this->flashy->error("Vous êtes déja connecté !");
            return $this->redirectToRoute('app_home');
        }

        $user = new Etudiant;

        $form = $this->createForm(RegistrationEtudiantFormType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On encode le mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                ->setRoles(['ROLE_ETUDIANT']);

            $em->persist($user);
            $em->flush();

            // Envoi de l'email pour l'activation du compte
            $this->emailVerifier->sendEmailConfirmation('app_verifier_email', $user,
            (new TemplatedEmail())
                ->from(new Address('deptinfo@ummto.com', 'DeptInfo - Département Informatique'))
                ->to($user->getEmail())
                ->subject('Vérification de votre adresse e-mail')
                ->htmlTemplate('emails/registration/confirmation_email.html.twig')
            );

            $this->flashy->success("Un e-mail d'activation a été envoyé à votre adresse e-mail.");

            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/etudiant.html.twig', [
            'formInscription' => $form->createView(),
            'etudiant' => true
        ]);
    }

    /**
     * @Route("/verifier/email", name="app_verifier_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        $id = $request->query->get('id'); 

        // Verifier si l'id existe et qu'il n'est pas null
        if ($id === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->find($id);

        // Si l'utilisateur existe
        if ($user === null) {
        return $this->redirectToRoute('app_login');
        }        

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason()));
            return $this->redirectToRoute('app_login');
        }
        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->flashy->success('Votre compte a été activé.');

        return $this->redirectToRoute('app_login');
    }
}
