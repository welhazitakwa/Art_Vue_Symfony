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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Utilisateur;
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
    #[Route('/add/{oeuvreId}', name: 'app_panier_add_oeuvre')]
    public function addToPanier(int $oeuvreId, Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        
        $quantite = max(1, (int)$request->request->get('quantite', 1));
    
        // Récupérer l'œuvre à partir de l'ID
        $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
    
        if (!$oeuvre) {
            throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
        }
    
      
    // Récupérer l'utilisateur actuel à partir de la session
    $userId = $session->get('user_id');

    // Récupérer le panier de l'utilisateur actuel
    $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);

    // Si l'utilisateur n'a pas encore de panier, vous pouvez créer un nouveau panier ici
    if (!$panier) {
        // Recherchez l'utilisateur à partir de son ID
        $user = $entityManager->getRepository(Utilisateur::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $panier = new Panier();
        $panier->setClient($user); // Assurez-vous que la relation client dans Panier est correctement configurée
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
    public function afficherPanier(PanierRepository $panierRepository,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {  // Récupérer l'utilisateur actuel à partir de la session
        $userId = $session->get('user_id');
    
        // Récupérer le panier de l'utilisateur actuel
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);
    
        
        if (!$panier) {
            throw $this->createNotFoundException('Le panier n\'existe pas.');
        }
        
        // Récupérer les œuvres ajoutées dans le panier
        $panieroeuvres = $panier->getPanieroeuvres();
        $totalPanier = 0;
        foreach ($panieroeuvres as $panieroeuvre) {
            $totalPanier += $panieroeuvre->getIdOeuvre()->getPrixvente() * $panieroeuvre->getQuantite();
        }
        return $this->render('panieroeuvre/afficher.html.twig', [
            'panieroeuvres' => $panieroeuvres,
            'totalPanier' => $totalPanier,

        ]);
    }
   
#[Route('/supprimer/{oeuvreId}', name: 'app_panier_supprimer_oeuvre')]
public function removeFromPanier(int $oeuvreId, EntityManagerInterface $entityManager,SessionInterface $session): Response
{
    // Récupérer l'œuvre à partir de l'ID
    $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
    
    if (!$oeuvre) {
        throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
    }
  // Récupérer l'utilisateur actuel à partir de la session
  $userId = $session->get('user_id');

  // Récupérer le panier de l'utilisateur actuel
  $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);

    
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
public function modifierQuantite(int $oeuvreId, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $quantite = max(1, (int)$request->request->get('quantite', 1));

    // Récupérer l'œuvre à partir de l'ID
    $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);

    if (!$oeuvre) {
        throw $this->createNotFoundException('L\'oeuvre n\'existe pas.');
    }
  // Récupérer l'utilisateur actuel à partir de la session
  $userId = $session->get('user_id');

  // Récupérer le panier de l'utilisateur actuel
  $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);


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

//tri
#[Route('/trier/{tri}', name: 'app_panieroeuvre_trier')]
public function trierPanier(string $tri, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
      // Récupérer l'utilisateur actuel à partir de la session
      $userId = $session->get('user_id');

      // Récupérer le panier de l'utilisateur actuel
      $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);
  
    
    if (!$panier) {
        throw $this->createNotFoundException('Le panier n\'existe pas.');
    }

// Récupérer les œuvres ajoutées dans le panier
$panieroeuvres = $panier->getPanieroeuvres()->toArray();
$totalPanier = 0;
foreach ($panieroeuvres as $panieroeuvre) {
    $totalPanier += $panieroeuvre->getIdOeuvre()->getPrixvente() * $panieroeuvre->getQuantite();
}
    // Trier les œuvres en fonction du critère choisi
  if ($tri === 'quantite') {
        usort($panieroeuvres, function($a, $b) {
            return $a->getQuantite() - $b->getQuantite();
        });
    } elseif ($tri === 'prix') {
        usort($panieroeuvres, function($a, $b) {
            return $a->getIdOeuvre()->getPrixvente() - $b->getIdOeuvre()->getPrixvente();
        });
    }

    return $this->render('panieroeuvre/afficher.html.twig', [
        'panieroeuvres' => $panieroeuvres,
        'totalPanier' => $totalPanier,

    ]);
}


}
