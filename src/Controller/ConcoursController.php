<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Entity\OeuvreConcours;
use App\Entity\Oeuvreart;
use App\Form\ConcoursType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/concours')]
class ConcoursController extends AbstractController
{
    #[Route('/', name: 'app_concours_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $concours = $entityManager
            ->getRepository(Concours::class)
            ->findAll();

        return $this->render('concours/index.html.twig', [
            'concours' => $concours,
        ]);
    }

    /*#[Route('/new', name: 'app_concours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $concour = new Concours();
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($concour);
            $entityManager->flush();

            return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concours/new.html.twig', [
            'concour' => $concour,
            'form' => $form,
        ]);
    }
*/
#[Route('/new', name: 'app_concours_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $concours = new Concours();
    $form = $this->createForm(ConcoursType::class, $concours);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les identifiants des œuvres sélectionnées à partir de la requête
        /** @var array $selectedOeuvresIds */
$selectedOeuvresIds = $request->request->get('concours')['oeuvres'];

        // Ajouter les relations entre le concours et les œuvres sélectionnées dans la table OeuvreConcours
        foreach ($selectedOeuvresIds as $oeuvreId) {
            $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
            if ($oeuvre) {
                $oeuvreConcours = new OeuvreConcours();
                $oeuvreConcours->setIdConcours($concours);
                $oeuvreConcours->setIdOeuvre($oeuvre);
                $entityManager->persist($oeuvreConcours);
            }
        }

        $entityManager->persist($concours);
        $entityManager->flush();

        return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('concours/new.html.twig', [
        'concours' => $concours,
        'form' => $form,
    ]);
}

   /* #[Route('/{id}', name: 'app_concours_show', methods: ['GET'])]
    public function show(Concours $concour): Response
    {
        return $this->render('concours/show.html.twig', [
            'concour' => $concour,
        ]);
    }*/
    #[Route('/{id}', name: 'app_concours_show', methods: ['GET'])]
public function show(Concours $concour): Response
{
    // Récupérer les œuvres associées à ce concours
    $oeuvres = $concour->getOeuvres();

    return $this->render('concours/show.html.twig', [
        'concour' => $concour,
        'oeuvres' => $oeuvres,
    ]);
}


  /*  #[Route('/{id}/edit', name: 'app_concours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concours/edit.html.twig', [
            'concour' => $concour,
            'form' => $form,
        ]);
    }*/
    #[Route('/{id}/edit', name: 'app_concours_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ConcoursType::class, $concour);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Supprimer les relations existantes entre le concours et les œuvres
        foreach ($concour->getOeuvres() as $oeuvre) {
            $oeuvreConcours = $entityManager->getRepository(OeuvreConcours::class)->findOneBy(['idConcours' => $concour, 'idOeuvre' => $oeuvre]);
            if ($oeuvreConcours) {
                $entityManager->remove($oeuvreConcours);
            }
        }

        // Récupérer les identifiants des nouvelles œuvres sélectionnées à partir du formulaire
        $selectedOeuvresIds = $form->get('oeuvres')->getData();

        // Ajouter les nouvelles relations entre le concours et les œuvres sélectionnées dans le formulaire
        foreach ($selectedOeuvresIds as $oeuvreId) {
            $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
            if ($oeuvre) {
                $oeuvreConcours = new OeuvreConcours();
                $oeuvreConcours->setIdConcours($concour);
                $oeuvreConcours->setIdOeuvre($oeuvre);
                $entityManager->persist($oeuvreConcours);
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('concours/edit.html.twig', [
        'concour' => $concour,
        'form' => $form,
    ]);
}




#[Route('/{id}', name: 'app_concours_delete', methods: ['POST'])]
public function delete(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$concour->getId(), $request->request->get('_token'))) {
        // Supprimer les relations avec les oeuvres
        foreach ($concour->getOeuvres() as $oeuvre) {
            $oeuvreConcours = $entityManager->getRepository(OeuvreConcours::class)->findOneBy(['idConcours' => $concour, 'idOeuvre' => $oeuvre]);
            if ($oeuvreConcours) {
                $entityManager->remove($oeuvreConcours);
            }
        }

        // Supprimer le concours lui-même
        $entityManager->remove($concour);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
}

}
