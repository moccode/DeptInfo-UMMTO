<?php

namespace App\Controller;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactController extends AbstractController
{

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/nous-contacter", name="app_contact")
     */
    public function nousContacter(Request $request, MailerInterface $mailer): Response
    {
        $user = $this->getUser();

        $form = $this->createFormBuilder()
                ->add('objet', TextType::class, [
                    'label' => 'Objet',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ est requis.',
                        ]),
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Ce champ ne doit pas être inférieure à {{ limit }} caractères !',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ])
                    ]
                ])
                ->add('message', TextareaType::class, [
                    'label' => 'Votre message',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ est requis.'
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Ce champ ne doit pas être inférieure à {{ limit }} caractères !',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ])
                    ]
                ])
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = (new TemplatedEmail())
                ->from(new Address($user->getEmail(), $user->getFullName()))
                ->to('admin@deptinfo.com')
                ->subject($form->get('objet')->getData())
                ->htmlTemplate('emails/contact/email.html.twig')
                ->context([
                    'message' => $form->get('message')->getData(),
                ])
            ;
        
        // On envoie le message de l'utilisateur à l'administrateur du site            
        $mailer->send($email);

        $this->flashy->success("Votre message a été envoyé.");

        return $this->redirectToRoute("app_home");
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
