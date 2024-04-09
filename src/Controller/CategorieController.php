<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Oeuvreart;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/homeAdmin', name: 'app_home', methods: ['GET'])]
    public function homeAdmin(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/homeClient', name: 'app_home_client', methods: ['GET'])]
    public function homeClient(): Response
    {
        return $this->render('baseClient.html.twig');
    }
  
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        $categoriesCount = $this->countCategories();
        $totalArtworksCount = $this->countTotalArtworks();
        $categoriesWithArtworksCount = [];
        foreach ($categories as $category) {
        $categoryId = $category->getIdcategorie();
        $artworksCount = $this->countArtworksByCategory($categoryId);
        $categoriesWithArtworksCount[$categoryId] = $artworksCount;
        $bestCategoryName = $this->bestCategory()->getContent();
    }

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'categoriesCount' => $categoriesCount,
            'categoriesWithArtworksCount' => $categoriesWithArtworksCount,
            'totalArtworksCount' => $totalArtworksCount,
            'bestCategoryName' => $bestCategoryName,
        ]);
    }



    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idcategorie}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{idcategorie}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idcategorie}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete( Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($categorie);
        $entityManager->flush();
        $entityManager->flush();


        return $this->redirectToRoute('app_categorie_index');
    }

    

    #[Route('/count', name: 'app_categorie_count', methods: ['GET'])]
    public function countCategories(): int
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(Categorie::class)->count([]);
    }

    #[Route('/countByCat', name: 'app_oeuver_count', methods: ['GET'])]
    public function countArtworksByCategory(int $categoryId): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count(['idCategorie' => $categoryId]);
}
#[Route('/countTotal', name: 'app_oeuvre_total', methods: ['GET'])]
public function countTotalArtworks(): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count([]);
}

#[Route('/best-category', name: 'app_best_category', methods: ['GET'])]
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



}
