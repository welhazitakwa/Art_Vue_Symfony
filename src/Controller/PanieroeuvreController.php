<?php

namespace App\Controller;

use App\Entity\Panieroeuvre;
use App\Form\PanieroeuvreType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Oeuvreart;
use App\Entity\Panier;
#[Route('/panieroeuvre')]
class PanieroeuvreController extends AbstractController
{
    #[Route('/', name: 'app_panieroeuvre_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panieroeuvre/index.html.twig', [
            'panieroeuvres' => $panierRepository->findAll(),
        ]);
    }
    private const PANIER_ID = 43; // Identifiant statique du panier
    #[Route('/add/{oeuvreId}', name: 'app_panier_add_oeuvre')]
    public function addToPanier(int $oeuvreId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantite = max(1, (int)$request->request->get('quantite', 1));
    
        // Récupérer l'œuvre à partir de l'ID
        $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
    
        if (!$oeuvre) {
            throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
        }
    
        // Récupérer le panier correspondant à l'identifiant statique ou créer un nouveau panier
        $panier = $entityManager->getRepository(Panier::class)->find(self::PANIER_ID);
    
        if (!$panier) {
            $panier = new Panier();
            $entityManager->persist($panier);
            $entityManager->flush();
        }
    
        // Vérifier si l'œuvre est déjà présente dans le panier
        $panieroeuvre = $entityManager->getRepository(Panieroeuvre::class)->findOneBy([
            'idOeuvre' => $oeuvre,
            'idPanier' => $panier
        ]);
    
        if ($panieroeuvre) {
            // Retourner une réponse JSON avec un message d'erreur
            return new JsonResponse(['error' => 'Cette œuvre est déjà dans votre panier.']);
        }
    
    
        // Créer une nouvelle association Panieroeuvre et l'ajouter au panier
        $panieroeuvre = new Panieroeuvre();
        $panieroeuvre->setIdOeuvre($oeuvre);
        $panieroeuvre->setIdPanier($panier);
        $panieroeuvre->setQuantite($quantite);
    
        $entityManager->persist($panieroeuvre);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_galerie');
    }

    #[Route('/afficher', name: 'app_panieroeuvre_afficher', methods: ['GET'])]
    public function afficherPanier(PanierRepository $panierRepository): Response
    {
        // Récupérer le panier correspondant à l'identifiant statique
        $panier = $this->getDoctrine()->getRepository(Panier::class)->find(self::PANIER_ID);
        
        if (!$panier) {
            throw $this->createNotFoundException('Le panier n\'existe pas.');
        }
        
        // Récupérer les œuvres ajoutées dans le panier
        $panieroeuvres = $panier->getPanieroeuvres();

        return $this->render('panieroeuvre/afficher.html.twig', [
            'panieroeuvres' => $panieroeuvres,
        ]);
    }
   
#[Route('/supprimer/{oeuvreId}', name: 'app_panier_supprimer_oeuvre')]
public function removeFromPanier(int $oeuvreId, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'œuvre à partir de l'ID
    $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
    
    if (!$oeuvre) {
        throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
    }

    // Récupérer le panier correspondant à l'identifiant statique
    $panier = $entityManager->getRepository(Panier::class)->find(self::PANIER_ID);
    
    if (!$panier) {
        throw $this->createNotFoundException('Le panier n\'existe pas.');
    }

    // Récupérer l'association Panieroeuvre correspondant à l'œuvre et au panier
    $panieroeuvre = $entityManager->getRepository(Panieroeuvre::class)->findOneBy([
        'idOeuvre' => $oeuvre,
        'idPanier' => $panier
    ]);
    
    if (!$panieroeuvre) {
        throw $this->createNotFoundException('L\'oeuvre n\'est pas présente dans le panier.');
    }

    // Supprimer l'association du panier
    $entityManager->remove($panieroeuvre);
    $entityManager->flush();

    // Rediriger vers la page d'affichage du panier
    return $this->redirectToRoute('app_panieroeuvre_afficher');
}
#[Route('/modifier/{oeuvreId}', name: 'app_panier_modifier_quantite')]
public function modifierQuantite(int $oeuvreId, Request $request, EntityManagerInterface $entityManager): Response
{
    $quantite = max(1, (int)$request->request->get('quantite', 1));

    // Récupérer l'œuvre à partir de l'ID
    $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);

    if (!$oeuvre) {
        throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
    }

    // Récupérer le panier correspondant à l'identifiant statique
    $panier = $entityManager->getRepository(Panier::class)->find(self::PANIER_ID);

    if (!$panier) {
        throw $this->createNotFoundException('Le panier n\'existe pas.');
    }

    // Récupérer la ligne de panier correspondant à l'œuvre dans le panier
    $panieroeuvre = $entityManager->getRepository(Panieroeuvre::class)->findOneBy([
        'idOeuvre' => $oeuvre,
        'idPanier' => $panier
    ]);

    if (!$panieroeuvre) {
        throw $this->createNotFoundException('Cette œuvre n\'est pas dans votre panier.');
    }

    // Mettre à jour la quantité de l'œuvre dans le panier
    $panieroeuvre->setQuantite($quantite);

    $entityManager->flush();

    return $this->redirectToRoute('app_panieroeuvre_afficher');
}



}
