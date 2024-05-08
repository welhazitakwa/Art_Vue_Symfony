<?php

namespace App\Controller;

use App\Entity\Offreenchere;
use App\Form\OffreenchereType;
use App\Repository\OffreenchereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VenteencheresRepository;
use App\Entity\Venteencheres;

#[Route('/offreenchere')]
class OffreenchereController extends AbstractController
{
    #[Route('/', name: 'app_offreenchere_index', methods: ['GET'])]
    public function index(OffreenchereRepository $offreenchereRepository, VenteencheresRepository $venteencheresRepository): Response
    {
        // Récupérer toutes les ventes aux enchères
        $venteencheres = $venteencheresRepository->findAll();

        // Créer un tableau pour stocker les offres associées à chaque vente aux enchères
        $offresParVente = [];

        // Pour chaque vente aux enchères, récupérer les offres associées
        foreach ($venteencheres as $venteenchere) {
            $offresParVente[$venteenchere->getId()] = $offreenchereRepository->findBy(['idVenteenchere' => $venteenchere->getId()]);
        }

        // Rendre la vue avec les ventes aux enchères et les offres associées
        return $this->render('offreenchere/index.html.twig', [
            'venteencheres' => $venteencheres,
            'offresParVente' => $offresParVente,
        ]);
    }

    #[Route('/new/{venteenchere_id}', name: 'app_offreenchere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $venteenchere_id, VenteencheresRepository $venteencheresRepository, OffreenchereRepository $offreenchereRepository): Response
    {
        // Récupérer la vente aux enchères correspondante
        $venteenchere = $venteencheresRepository->find($venteenchere_id);
    
        if (!$venteenchere) {
            throw $this->createNotFoundException('La vente aux enchères correspondante n\'existe pas.');
        }
    
        // Récupérer l'offre précédente associée à la même vente
        $offrePrecedente = $offreenchereRepository->findOneBy(['idVenteenchere' => $venteenchere], ['date' => 'DESC']);
    
        // Si aucune offre précédente n'existe, comparer avec le prix de départ de la vente
        $prixComparaison = $offrePrecedente ? $offrePrecedente->getMontant() : $venteenchere->getPrixdepart();
    
        // Créer une nouvelle instance d'Offreenchere
        $offreenchere = new Offreenchere();
        $offreenchere->setIdVenteenchere($venteenchere);
    
        // Créer le formulaire
        $form = $this->createForm(OffreenchereType::class, $offreenchere);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Comparaison entre le montant de l'offre et le montant de comparaison
            if ($offreenchere->getMontant() > $prixComparaison) {
                // Supprimer l'offre précédente s'il existe
                if ($offrePrecedente) {
                    $entityManager->remove($offrePrecedente);
                }
                $entityManager->persist($offreenchere);
                $entityManager->flush();
    
                // Ajouter un message flash de succès
                $this->addFlash('success', 'Votre offre a été ajoutée avec succès.');
                return $this->redirectToRoute('app_offreenchere_index');
            } else {
                // Ajouter un message flash d'erreur si l'offre n'est pas supérieure à la comparaison
                $this->addFlash('error', 'Votre offre doit être supérieure à l\'offre précédente ou au prix de départ de la vente.');
            }
        }
    
        // Rendre la vue avec les données nécessaires
        return $this->render('offreenchere/new.html.twig', [
            'offreenchere' => $offreenchere,
            'venteenchere' => $venteenchere,
            'prixComparaison' => $prixComparaison,
            'form' => $form->createView(),
        ]);
    }
    
    


    #[Route('/{id}', name: 'app_offreenchere_show', methods: ['GET'])]
    public function show(Offreenchere $offreenchere): Response
    {
        return $this->render('offreenchere/show.html.twig', [
            'offreenchere' => $offreenchere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offreenchere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offreenchere $offreenchere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreenchereType::class, $offreenchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offreenchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offreenchere/edit.html.twig', [
            'offreenchere' => $offreenchere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offreenchere_delete', methods: ['POST'])]
    public function delete(Request $request, Offreenchere $offreenchere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offreenchere->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offreenchere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offreenchere_index', [], Response::HTTP_SEE_OTHER);
    }
}
