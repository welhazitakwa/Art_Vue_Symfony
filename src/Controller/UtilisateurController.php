<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/login', name: 'login_user')]
    public function login ():Response{
       
        return $this->render('Utilisateur/login.html.twig', [
            
        ]);
        
    }
    #[Route('/registre', name: 'registre_user')]
    public function registre ():Response{
       
        return $this->render('Utilisateur/registre.html.twig', [
            
        ]);
        
    }






}
