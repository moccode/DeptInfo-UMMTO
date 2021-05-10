<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        
        if(in_array("ROLE_ADMIN", $this->getUser()->getRoles())){
            return $this->redirectToRoute("admin_home");
        }
        else {
            return $this->render('home/index.html.twig');
        }
    
    }
}
