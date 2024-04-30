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

#[Route('/offreenchere')]
class OffreenchereController extends AbstractController
{
    #[Route('/', name: 'app_offreenchere_index', methods: ['GET'])]
    public function index(OffreenchereRepository $offreenchereRepository): Response
    {
        return $this->render('offreenchere/index.html.twig', [
            'offreencheres' => $offreenchereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_offreenchere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offreenchere = new Offreenchere();
        $form = $this->createForm(OffreenchereType::class, $offreenchere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offreenchere);
            $entityManager->flush();

            return $this->redirectToRoute('app_offreenchere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offreenchere/new.html.twig', [
            'offreenchere' => $offreenchere,
            'form' => $form,
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
