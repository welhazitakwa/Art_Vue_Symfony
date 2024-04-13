<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Oeuvreart;
use App\Repository\OeuvreartRepository;

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

    #[Route('/galerieCategorie/{idoeuvreart}', name: 'app_galerie_show', methods: ['GET'])]
    public function show(Request $request, EntityManagerInterface $entityManager, $idoeuvreart): Response
    {
        // Appeler la méthode showAction pour récupérer les données
        $showActionResult = $this->showAction($idoeuvreart);
    
        // Extraire les données récupérées de la méthode showAction
        $oeuvreArt = $showActionResult['oeuvreArt'];
        $similarOeuvres = $showActionResult['similarOeuvres'];
    
        return $this->render('galerie/showGalerie.html.twig', [
            'oeuvreart' => $oeuvreArt,
            'similarOeuvres' => $similarOeuvres,
        ]);
    }
    
    // Méthode showAction mise à jour pour être utilisée par d'autres méthodes
    private function showAction($idoeuvreart) // Ou toute autre méthode pour afficher les détails d'une œuvre
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // Récupérer l'œuvre d'art actuelle
        $oeuvreArt = $entityManager->getRepository(OeuvreArt::class)->find($idoeuvreart);
    
        // Vérifier si l'œuvre d'art existe
        if (!$oeuvreArt) {
            throw $this->createNotFoundException('Aucune œuvre d\'art trouvée pour l\'ID '.$idoeuvreart);
        }
    
        // Récupérer la catégorie de l'œuvre actuelle
        $categorieId = $oeuvreArt->getIdCategorie();
    
        // Récupérer toutes les œuvres de la même catégorie
        $similarOeuvres = $entityManager->getRepository(OeuvreArt::class)->findBy(['idCategorie' => $categorieId]);
    
        // Retourner les données
        return [
            'oeuvreArt' => $oeuvreArt,
            'similarOeuvres' => $similarOeuvres,
        ];
    }


    #[Route('/galerieCategorieAff/{idcategorie}', name: 'app_galerie_by_categorieAff', methods: ['GET'])]
    public function showByCategorie(int $idcategorie, OeuvreartRepository $oeuvreartRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer la catégorie
        $categorie = $entityManager->getRepository(Categorie::class)->find($idcategorie);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            throw $this->createNotFoundException('Aucune catégorie trouvée pour l\'ID '.$idcategorie);
        }

        // Récupérer les œuvres d'art de la catégorie sélectionnée en utilisant le repository
        $oeuvrearts = $oeuvreartRepository->findByCategorie($categorie);

        return $this->render('galerie/galerie.html.twig', [
            'categories' => [$categorie], // Passer la catégorie sous forme de tableau
            'oeuvrearts' => $oeuvrearts,
        ]);
    }

    #[Route('/lastThreeAddedArtworks', name: 'app_lastThreeAddedArtworks')]
    public function lastThreeAddedArtworks(OeuvreartRepository $oeuvreartRepository): Response
    {
        $lastThreeAddedArtworks = $oeuvreartRepository->findLastThreeAddedArtworks();

        return $this->render('test.html.twig', [
            'lastThreeAddedArtworks' => $lastThreeAddedArtworks,
        ]);
    }
    

}
