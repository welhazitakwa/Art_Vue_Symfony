<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Criteria;
use App\Repository\PanieroeuvreRepository;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        $panier = new Panier(); // Appel du constructeur pour initialiser la date d'ajout
    $form = $this->createForm(PanierType::class, $panier);
    $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $client = $panier->getClient();

            // Vérifier si le client a déjà un panier
            $existingPanier = $panierRepository->findOneBy(['client' => $client]);
    
            if ($existingPanier) {
                // Si un panier existe déjà pour ce client, afficher une alerte
                $this->addFlash('error', 'Ce client possède déjà un panier.');
            } else {
                // Si le client n'a pas de panier existant, continuer avec l'ajout du nouveau panier
                $panier->setDateajout(new \DateTime());
                $entityManager->persist($panier);
                $entityManager->flush();
                return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(), // Utilisez createView() pour obtenir un objet FormView
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }

//commander panier
  
#[Route('/panier/commander', name: 'commander_panier')]
public function commanderPanier(EntityManagerInterface $entityManager, Request $request): Response
{
    $panierId = 43; // Exemple : Récupérer l'ID du panier à partir de la session ou d'une autre méthode
    
    // Récupérer le panier à partir de l'ID
    $panier = $this->getDoctrine()->getRepository(Panier::class)->find($panierId);

    // Calculer le montant total de la commande
    $montantTotal = 0;
    foreach ($panier->getPanieroeuvres() as $panieroeuvre) {
        $montantTotal += $panieroeuvre->getIdOeuvre()->getPrixvente() * $panieroeuvre->getQuantite();
    }

    // Si le formulaire de confirmation est soumis
    if ($request->isMethod('POST')) {
        // Récupérer l'utilisateur actuel associé au panier
        $client = $panier->getClient();
        
        // Mettre à jour l'adresse de l'utilisateur avec la nouvelle valeur du champ d'adresse du formulaire
        $nouvelleAdresse = $request->request->get('adresse');
        $client->setAdresse($nouvelleAdresse);
        
        // Enregistrer les modifications de l'utilisateur dans la base de données
        $entityManager->persist($client);
        $entityManager->flush();

        // Créer une nouvelle instance de Commande
        $commande = new Commande();
        $commande->setMontant($montantTotal);
        $commande->setDate(new \DateTime());
        $commande->setEtat('envoyé');
        $commande->setPanier($panier);

        // Enregistrer la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Supprimer tous les éléments du panier après la commande
        foreach ($panier->getPanieroeuvres() as $panieroeuvre) {
            $panier->removePanieroeuvre($panieroeuvre);
            $entityManager->remove($panieroeuvre);
        }
        $entityManager->flush();

        // Rediriger l'utilisateur vers une page de confirmation de commande réussie
        return $this->redirectToRoute('app_panieroeuvre_afficher');
    }
   
    // Si le formulaire de confirmation n'est pas soumis, afficher la page de confirmation
    return $this->render('panieroeuvre/confirmation_commande.html.twig', [
        'panier' => $panier,
        'montantTotal' => $montantTotal,
        'nom' => $panier->getClient()->getNom(),
        'prenom' => $panier->getClient()->getPrenom(),
        'adresse' => $panier->getClient()->getAdresse(),

    ]);
}



}


