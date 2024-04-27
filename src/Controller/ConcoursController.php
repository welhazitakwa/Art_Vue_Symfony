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


use Symfony\Component\HttpFoundation\JsonResponse;


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
    
    #[Route('/showClient', name: 'app_concoursclient_index', methods: ['GET'])]
    public function indexClient(EntityManagerInterface $entityManager): Response
    {
        $concours = $entityManager
            ->getRepository(Concours::class)
            ->findAll();

        return $this->render('concours/showClient.html.twig', [
            'concours' => $concours,
        ]);
    }
    

    

#[Route('/concours/new', name: 'app_concours_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $concours = new Concours();
    $form = $this->createForm(ConcoursType::class, $concours);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($concours);

        // Récupérer les données soumises du formulaire
        $selectedOeuvres = $request->request->get('concours')['oeuvres'] ?? [];

        // Associer les œuvres sélectionnées au concours
        foreach ($selectedOeuvres as $oeuvreId) {
            $oeuvre = $entityManager->getRepository(Oeuvreart::class)->find($oeuvreId);
            if ($oeuvre) {
                $oeuvreConcours = new OeuvreConcours();
                $oeuvreConcours->setIdConcours($concours);
                $oeuvreConcours->setIdOeuvre($oeuvre);
                $entityManager->persist($oeuvreConcours);
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('concours/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


  


 
    #[Route('/{id}/edit', name: 'app_concours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer les anciennes relations dans la table OeuvresConcours
            $oldRelations = $entityManager->getRepository(OeuvreConcours::class)->findBy(['idConcours' => $concour]);
            foreach ($oldRelations as $relation) {
                $entityManager->remove($relation);
            }
    
            // Récupérer les identifiants des nouvelles œuvres sélectionnées à partir du formulaire
            $selectedOeuvresIds = $form->get('oeuvres')->getData();
    
            // Ajouter les nouvelles relations dans la table OeuvresConcours
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

#[Route('/concours/{id}', name: 'app_concours_show')]
public function show($id, EntityManagerInterface $entityManager): Response
{
    // Récupérer le concours associé à l'ID
    $concoursRepository = $entityManager->getRepository(Concours::class);
    $concours = $concoursRepository->find($id);

    // Vérifier si le concours existe
    if (!$concours) {
        throw $this->createNotFoundException('Le concours avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Récupérer les oeuvreConcours associés à ce concours
    $oeuvreConcoursRepository = $entityManager->getRepository(OeuvreConcours::class);
    $oeuvreConcours = $oeuvreConcoursRepository->findBy(['idConcours' => $id]);

    // Récupérer les œuvres à partir des oeuvreConcours trouvés
    $oeuvres = [];
    foreach ($oeuvreConcours as $oc) {
        $oeuvres[] = $oc->getIdOeuvre();
    }

    // Passer les données à la vue Twig pour l'affichage
    return $this->render('concours/show.html.twig', [
        'concours' => $concours,
        'oeuvres' => $oeuvres,
    ]);
}


#[Route('/concours/{id}/oeuvres', name: 'app_concours_oeuvres', methods: ['GET'])]
public function showOeuvres($id, EntityManagerInterface $entityManager): Response
{
    $concours = $entityManager->getRepository(Concours::class)->find($id);

    // Vérifier si le concours existe
    if (!$concours) {
        throw $this->createNotFoundException('Le concours avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    $oeuvreConcoursRepository = $entityManager->getRepository(OeuvreConcours::class);

    // Récupérer les oeuvreConcours associés à ce concours
    $oeuvreConcours = $oeuvreConcoursRepository->findBy(['idConcours' => $id]);

    // Récupérer les oeuvres à partir des oeuvreConcours trouvés
    $oeuvres = [];
    foreach ($oeuvreConcours as $oc) {
        $oeuvres[] = $oc->getIdOeuvre();
    }

    return $this->render('concours/oeuvreConcoursClient.html.twig', [
        'concours' => $concours,
        'oeuvres' => $oeuvres,
    ]);
}
#[Route('/calendrier', name: 'app_calendrier')]
    public function showCalendar(): Response
    {
        // Rendre le template du calendrier
        return $this->render('concours/concoursCalender.html.twig');
    }

    #[Route('/calendar/events', name: 'calendar_events', methods: ['GET'])]
    public function getCalendarEvents(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer tous les concours
        $concours = $entityManager->getRepository(Concours::class)->findAll();

        $events = [];

        // Convertir les concours en événements FullCalendar
        foreach ($concours as $concour) {
            $events[] = [
                'title' => $concour->getTitre(),
                'start' => $concour->getDateDebut()->format('Y-m-d'),
                'end' => $concour->getDateFin()->format('Y-m-d'),
            ];
        }

        // Retourner les événements au format JSON
        return new JsonResponse($events);
    }
}