<?php

namespace App\Controller;

use App\Entity\Oeuvreart;
use App\Entity\Categorie;
use App\Form\OeuvreartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Repository\OeuvreartRepository;

#[Route('/oeuvreart')]
class OeuvreartController extends AbstractController
{
    #[Route('/', name: 'app_oeuvreart_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , OeuvreartRepository $oeuvreartsRepository): Response
    {
        
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

        return $this->render('oeuvreart/index.html.twig', [
            'oeuvrearts' => $oeuvreartsRepository->trie_decroissant_date(),
            
            'categories' => $categories,
            'categoriesCount' => $categoriesCount,
            'categoriesWithArtworksCount' => $categoriesWithArtworksCount,
            'totalArtworksCount' => $totalArtworksCount,
            'bestCategoryName' => $bestCategoryName,
            
        ]);
    }

        #[Route('/new', name: 'app_oeuvreart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    
    $oeuvreart = new Oeuvreart();
    $form = $this->createForm(OeuvreartType::class, $oeuvreart);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $oeuvreart->setImage($fileName);
        $entityManager->persist($oeuvreart);
        $entityManager->flush();

        // Redirection vers la page d'index des œuvres d'art
        return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
    }

    // Affichage du formulaire
    return $this->renderForm('oeuvreart/new.html.twig', [
        'oeuvreart' => $oeuvreart,
        'form' => $form,
    ]);
}


//     #[Route('/new', name: 'app_oeuvreart_new', methods: ['GET', 'POST'])]
// public function new(Request $request, EntityManagerInterface $entityManager): Response
// {
    
//     $oeuvreart = new Oeuvreart();
//     $userId = 14;
//     $user = $entityManager->getRepository(Utilisateur::class)->find($userId);
//     $oeuvreart->setIdArtiste($user);
//     $form = $this->createForm(OeuvreartType::class, $oeuvreart);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $file = $form->get('image')->getData();
//             $fileName = uniqid().'.'.$file->guessExtension();
//             $file->move($this->getParameter('images_directorys'), $fileName);
//             $oeuvreart->setImage($fileName);
//         $entityManager->persist($oeuvreart);
//         $entityManager->flush();

//         // Redirection vers la page d'index des œuvres d'art
//         return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
//     }

//     // Affichage du formulaire
//     return $this->renderForm('oeuvreart/new.html.twig', [
//         'oeuvreart' => $oeuvreart,
//         'form' => $form,
//     ]);
// }


    
    #[Route('/{idoeuvreart}', name: 'app_oeuvreart_show', methods: ['GET'])]
    public function show(Oeuvreart $oeuvreart): Response
    {
        return $this->render('oeuvreart/show.html.twig', [
            'oeuvreart' => $oeuvreart,
        ]);
    }

    #[Route('/{idoeuvreart}/edit', name: 'app_oeuvreart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvreart $oeuvreart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OeuvreartType::class, $oeuvreart, [
            'attr' => ['enctype' => 'multipart/form-data'],
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            // Vérifier si une nouvelle image a été téléchargée
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('images_directorys'), $fileName);
                $oeuvreart->setImage($fileName);
            }
            // Si aucune nouvelle image n'a été téléchargée, conserver l'image existante
            else {
                $oeuvreart->setImage($oeuvreart->getImage());
            }
            $oeuvreart->setDateajout(new \DateTime());
            $entityManager->flush();
    
            return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('oeuvreart/edit.html.twig', [
            'oeuvreart' => $oeuvreart,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idoeuvreart}', name: 'app_oeuvreart_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvreart $oeuvreart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvreart->getIdoeuvreart(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvreart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
    } 

    #[Route('/count', name: 'app_categorie_count2', methods: ['GET'])]
    public function countCategories(): int
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(Categorie::class)->count([]);
    }

    #[Route('/countByCat', name: 'app_oeuver_count2', methods: ['GET'])]
    public function countArtworksByCategory(int $categoryId): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count(['idCategorie' => $categoryId]);
}
#[Route('/countTotal', name: 'app_oeuvre_total2', methods: ['GET'])]
public function countTotalArtworks(): int
{
    $entityManager = $this->getDoctrine()->getManager();
    return $entityManager->getRepository(Oeuvreart::class)->count([]);
}

#[Route('/best-category', name: 'app_best_category2', methods: ['GET'])]
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
