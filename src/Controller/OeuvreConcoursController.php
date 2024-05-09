<?php

namespace App\Controller;

use App\Entity\OeuvreConcours;
use App\Form\OeuvreConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/oeuvre/concours')]
class OeuvreConcoursController extends AbstractController
{
    #[Route('/', name: 'app_oeuvre_concours_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $oeuvreConcours = $entityManager
            ->getRepository(OeuvreConcours::class)
            ->findAll();

        return $this->render('oeuvre_concours/index.html.twig', [
            'oeuvre_concours' => $oeuvreConcours,
        ]);
    }

    #[Route('/new', name: 'app_oeuvre_concours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvreConcour = new OeuvreConcours();
        $form = $this->createForm(OeuvreConcoursType::class, $oeuvreConcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oeuvreConcour);
            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvre_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre_concours/new.html.twig', [
            'oeuvre_concour' => $oeuvreConcour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_concours_show', methods: ['GET'])]
    public function show(OeuvreConcours $oeuvreConcour): Response
    {
        return $this->render('oeuvre_concours/show.html.twig', [
            'oeuvre_concour' => $oeuvreConcour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_oeuvre_concours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OeuvreConcours $oeuvreConcour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OeuvreConcoursType::class, $oeuvreConcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvre_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre_concours/edit.html.twig', [
            'oeuvre_concour' => $oeuvreConcour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_concours_delete', methods: ['POST'])]
    public function delete(Request $request, OeuvreConcours $oeuvreConcour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvreConcour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvreConcour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_oeuvre_concours_index', [], Response::HTTP_SEE_OTHER);
    }
}
