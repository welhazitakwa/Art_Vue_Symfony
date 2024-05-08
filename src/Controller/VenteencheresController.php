<?php

namespace App\Controller;

use App\Entity\Venteencheres;
use App\Form\VenteencheresType;
use App\Repository\VenteencheresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/venteencheres')]
class VenteencheresController extends AbstractController
{
    #[Route('/', name: 'app_venteencheres_index', methods: ['GET'])]
    public function index(VenteencheresRepository $venteencheresRepository): Response
    {
        return $this->render('venteencheres/index.html.twig', [
            'venteencheres' => $venteencheresRepository->findAll(),
        ]);
    }
    #[Route('/back', name: 'app_venteencheres_index1', methods: ['GET'])]
    public function index1(VenteencheresRepository $venteencheresRepository): Response
    {
        return $this->render('venteencheresb/index.html.twig', [
            'venteencheres' => $venteencheresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_venteencheres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $venteenchere = new Venteencheres();
        $form = $this->createForm(VenteencheresType::class, $venteenchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($venteenchere);
            $entityManager->flush();

            return $this->redirectToRoute('app_venteencheres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('venteencheres/new.html.twig', [
            'venteenchere' => $venteenchere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_venteencheres_show', methods: ['GET'])]
    public function show(Venteencheres $venteenchere): Response
    {
        return $this->render('venteencheres/show.html.twig', [
            'venteenchere' => $venteenchere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_venteencheres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Venteencheres $venteenchere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VenteencheresType::class, $venteenchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_venteencheres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('venteencheres/edit.html.twig', [
            'venteenchere' => $venteenchere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_venteencheres_delete', methods: ['POST'])]
    public function delete(Request $request, Venteencheres $venteenchere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$venteenchere->getId(), $request->request->get('_token'))) {
            $entityManager->remove($venteenchere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_venteencheres_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/venteencheres/search', name: 'app_venteencheres_search', methods: ['GET'])]
    public function search(Request $request, VenteencheresRepository $venteencheresRepository): JsonResponse
    {
        $searchTerm = $request->query->get('q');
    
        // Effectuer la recherche dans la base de données
        $results = $venteencheresRepository->search($searchTerm);
    
        // Retourner les résultats au format JSON
        return $this->json($results);
    }
    
}
