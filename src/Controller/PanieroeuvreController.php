<?php

namespace App\Controller;

use App\Entity\Panieroeuvre;
use App\Form\PanieroeuvreType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/new', name: 'app_panieroeuvre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panieroeuvre = new Panieroeuvre();
        $form = $this->createForm(PanieroeuvreType::class, $panieroeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panieroeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('app_panieroeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panieroeuvre/new.html.twig', [
            'panieroeuvre' => $panieroeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panieroeuvre_show', methods: ['GET'])]
    public function show(Panieroeuvre $panieroeuvre): Response
    {
        return $this->render('panieroeuvre/show.html.twig', [
            'panieroeuvre' => $panieroeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panieroeuvre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panieroeuvre $panieroeuvre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanieroeuvreType::class, $panieroeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panieroeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panieroeuvre/edit.html.twig', [
            'panieroeuvre' => $panieroeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panieroeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Panieroeuvre $panieroeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panieroeuvre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panieroeuvre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panieroeuvre_index', [], Response::HTTP_SEE_OTHER);
    }
}
