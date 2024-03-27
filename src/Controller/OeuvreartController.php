<?php

namespace App\Controller;

use App\Entity\Oeuvreart;
use App\Form\OeuvreartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/oeuvreart')]
class OeuvreartController extends AbstractController
{
   /* #[Route('/', name: 'app_oeuvreart_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $oeuvrearts = $entityManager
            ->getRepository(Oeuvreart::class)
            ->findAll();

        return $this->render('oeuvreart/index.html.twig', [
            'oeuvrearts' => $oeuvrearts,
        ]);
    }

    #[Route('/new', name: 'app_oeuvreart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvreart = new Oeuvreart();
        $form = $this->createForm(OeuvreartType::class, $oeuvreart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oeuvreart);
            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvreart/new.html.twig', [
            'oeuvreart' => $oeuvreart,
            'form' => $form,
        ]);
    }


    
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
        $form = $this->createForm(OeuvreartType::class, $oeuvreart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    }  */
}
