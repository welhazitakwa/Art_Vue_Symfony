<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Oeuvreart;
use App\Repository\OeuvreartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'app_stat')]
    public function index(EntityManagerInterface $entityManager , OeuvreartRepository $oeuvreartsRepository): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $categoriesCount1 = $this->countCategories1();
        $totalArtworksCount = $this->countTotalArtworks();
         $totalArtworksCount = $this->countTotalArtworks();
            $categoriesWithArtworksCount = [];
            foreach ($categories as $category) {
            $categoryId = $category->getIdcategorie();
            $artworksCount = $this->countArtworksByCategory($categoryId);
            $categoriesWithArtworksCount[$categoryId] = $artworksCount;
            $bestCategoryName = $this->bestCategory()->getContent();
        }
        return $this->render('oeuvreart/stat.html.twig', [
            'categoriesCount1' => $categoriesCount1,
            'categories' => $categories,
            'categoriesWithArtworksCount' => $categoriesWithArtworksCount,
            'totalArtworksCount' => $totalArtworksCount,
            'bestCategoryName' => $bestCategoryName,
            'controller_name' => 'StatController',
        ]);
    }

    #[Route('/count/stat', name: 'app_categorie_count3', methods: ['GET'])]
    public function countCategories1(): int
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(Categorie::class)->count([]);
    }

    #[Route('/countTotal/stat', name: 'app_oeuvre_total2', methods: ['GET'])]
public function countTotalArtworks(): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count([]);
}

#[Route('/countByCat/stat', name: 'app_oeuver_count2', methods: ['GET'])]
    public function countArtworksByCategory(int $categoryId): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count(['idCategorie' => $categoryId]);
}

#[Route('/best-category/stat', name: 'app_best_category2', methods: ['GET'])]
public function bestCategory(): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Récupérer toutes les catégories
    $categories = $entityManager->getRepository(Categorie::class)->findAll();

    // Initialiser la meilleure catégorie et son nombre d'œuvres d'art
    $bestCategory = null;
    $maxArtworksCount = 0;

    // Parcourir les catégories pour trouver la meilleure
    foreach ($categories as $category) {
        $categoryId = $category->getIdcategorie();
        $artworksCount = $this->countArtworksByCategory($categoryId);

        // Mettre à jour la meilleure catégorie si nécessaire
        if ($artworksCount > $maxArtworksCount) {
            $bestCategory = $category;
            $maxArtworksCount = $artworksCount;
        }
    }

    // Retourner le nom de la meilleure catégorie
    return new Response($bestCategory->getNomcategorie());
}
    
    #[Route('/chart/prix-ventee', name: 'chart_prix_vente')]
public function prixVenteChart(): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $repository = $entityManager->getRepository(Oeuvreart::class);
    $prixVenteData = $repository->getPrixVenteData();

    return $this->render('oeuvreart/stat.html.twig', ['prixVenteData' => $prixVenteData]);
}


#[Route('/chart/oeuvre-par-categorie', name: 'chart_oeuvre_par_categorie')]
public function oeuvreParCategorieChart(): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $categories = $entityManager->getRepository(Categorie::class)->findAll();

    $data = [];
    foreach ($categories as $category) {
        $categoryId = $category->getIdcategorie();
        $artworksCount = $this->countArtworksByCategory($categoryId);
        $data[$category->getNomcategorie()] = $artworksCount;
    }

    return $this->render('oeuvreart/oeuvre_par_categorie_chart.html.twig', ['data' => $data]);
}


}
