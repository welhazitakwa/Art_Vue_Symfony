<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Oeuvreart;

class GalerieController extends AbstractController
{
    #[Route('/galerieCategorie', name: 'app_galerie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $oeuvrearts = $entityManager->getRepository(Oeuvreart::class)->findAll();
        
        return $this->render('galerie/galerie.html.twig', [
            'categories' => $categories,
            'oeuvrearts' => $oeuvrearts,
        ]);
    }
    
    #[Route('/galerieOeuvre', name: 'app_oeuvreart_galerie', methods: ['GET'])]
    public function OeuvreArt(EntityManagerInterface $entityManager): Response
    {
        $oeuvrearts = $entityManager->getRepository(Oeuvreart::class)->findAll();
        
        return $oeuvrearts;
    }
}
