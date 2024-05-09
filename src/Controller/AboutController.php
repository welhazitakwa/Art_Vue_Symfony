<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/about.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }

    #[Route('/aboutArtiste', name: 'app_aboutArtiste')]
    public function index2(): Response
    {
        return $this->render('about/aboutArtiste.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
}
