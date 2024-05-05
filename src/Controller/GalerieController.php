<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categorie;
use App\Entity\Likes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Oeuvreart;
use App\Entity\Utilisateur;
use App\Repository\LikesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\OeuvreartRepository;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;

class GalerieController extends AbstractController
{

    #[Route('/galerieCategorie', name: 'app_galerie')]
    public function index(Request $request, EntityManagerInterface $entityManager, OeuvreartRepository $oeuvreartRepository, LikesRepository $likesRepository): Response
    {
        // Récupérer toutes les catégories
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
    
        // Récupérer tous les œuvres d'art par défaut
        $oeuvrearts = $entityManager->getRepository(Oeuvreart::class)->findAll();
    
        // Récupérer les paramètres de recherche
        $search = $request->query->get('search');
        $searchCategory = $request->query->get('category');
    
        // Filtrer les œuvres d'art en fonction des critères de recherche
        if ($search || $searchCategory) {
            $oeuvrearts = $oeuvreartRepository->findByArtistAndCategory($search, $searchCategory);
        }
        
        // Initialiser un tableau pour stocker le nombre de likes pour chaque œuvre
        $likesCountForEachOeuvre = [];

        // Récupérer le nombre de likes pour chaque œuvre d'art
        foreach ($oeuvrearts as $oeuvreart) {
            $oeuvreId = $oeuvreart->getIdoeuvreart();
            $likesCount = $likesRepository->countLikesForOeuvreArt($oeuvreId);
            $likesCountForEachOeuvre[$oeuvreId] = $likesCount;
        }

        return $this->render('galerie/galerie.html.twig', [
            'categories' => $categories,
            'oeuvrearts' => $oeuvrearts,
            'likesCountForEachOeuvre' => $likesCountForEachOeuvre, // Passer les résultats à la vue Twig
        ]);
    }


    
    //---------QR CODE--------------------
    #[Route('/oeuvre/qr-code/{id}', name: 'oeuvre_qr_code')]
    public function generateQRCode(int $id, OeuvreartRepository $oeuvreartRepository): Response
    {
        $oeuvreArt = $oeuvreartRepository->find($id); 
        $qrCodeContent = "\n****Information d'artiste**** ";
        $qrCodeContent .= "\nNom: " . $oeuvreArt->getIdArtiste()->getNom();
        $qrCodeContent .= "\nPrenom: " . $oeuvreArt->getIdArtiste()->getPrenom();
        $qrCodeContent .= "\nAdresse: " . $oeuvreArt->getIdArtiste()->getAdresse();
        $qrCodeContent .= "\nEmail: " . $oeuvreArt->getIdArtiste()->getEmail();
        $qrCodeContent .= "\nTelephone: " . $oeuvreArt->getIdArtiste()->getNumtel();
        
        // Add more details as needed
    
        $qrCode = new QrCode($qrCodeContent);
        $qrCode->setSize(200);
        $qrCode->setMargin(10);
    
        // Create QR code image
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
    
        // Return response with QR code image
        return new Response($result->getString(), Response::HTTP_OK, ['Content-Type' => $result->getMimeType()]);
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


    #[Route('/like/{id}/toggle', name: 'like_toggle')]
    public function toggleLike(Request $request, $id, SessionInterface $session, LikesRepository $likesRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // Get the current user
        $user = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));;
        
        // Get the Oeuvreart entity with the provided ID
        $oeuvreArt = $entityManager->getRepository(Oeuvreart::class)->find($id);
        
        // If the Oeuvreart with the provided ID doesn't exist, handle the error (return a response or throw an exception)
        
        // Check if the user already liked this oeuvre
        $existingLike = $likesRepository->findOneBy(['idutilisateur' => $user, 'idoeuvreart' => $oeuvreArt]);
        
        // If the user already liked the oeuvre, toggle the like
        if ($existingLike) {
            $existingLike->setEstlike(!$existingLike->getEstlike());
        } else {
            // If the user hasn't liked the oeuvre yet, create a new like
            $newLike = new Likes();
            $newLike->setIdutilisateur($user);
            $newLike->setIdoeuvreart($oeuvreArt);
            $newLike->setEstlike(true);
            $entityManager->persist($newLike);
        }
        
        $entityManager->flush();
        
        // Redirect back to the previous page
        return $this->redirect($request->headers->get('referer'));
    }






    #[Route('/calculate-likes', name: 'calculate_likes')]
public function calculateLikes(): JsonResponse
{
    // Récupérer le gestionnaire d'entités
    $entityManager = $this->getDoctrine()->getManager();

    // Récupérer tous les likes où estLike est true
    $likes = $entityManager->getRepository(Likes::class)->findBy(['estlike' => true]);

    // Initialiser un tableau pour stocker le nombre de likes pour chaque œuvre
    $likesCountForEachOeuvre = [];

    // Parcourir les likes et compter le nombre de likes pour chaque œuvre
    foreach ($likes as $like) {
        $oeuvreId = $like->getIdoeuvreart()->getIdoeuvreart();
        if (!isset($likesCountForEachOeuvre[$oeuvreId])) {
            $likesCountForEachOeuvre[$oeuvreId] = 1;
        } else {
            $likesCountForEachOeuvre[$oeuvreId]++;
        }
    }

    // Retourner les résultats sous forme de réponse JSON
    return new JsonResponse($likesCountForEachOeuvre);
}


    

    
}







