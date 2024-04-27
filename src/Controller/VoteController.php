<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Entity\Utilisateur;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Entity\Oeuvreart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
#[Route('/vote')]
class VoteController extends AbstractController

{

    #[Route('/', name: 'app_vote_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
       
          $userId = 50;

          // Récupérer les votes de l'utilisateur avec l'ID 50
          $user = $entityManager->getRepository(Utilisateur::class)->find($userId);
          $votes = $entityManager
              ->getRepository(Vote::class)
              ->findBy(['user' => $user]);
  
          return $this->render('vote/index.html.twig', [
              'votes' => $votes,
          ]);
    }

    #[Route('/new', name: 'app_vote_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données de la requête
      
     
        $concoursId = $request->request->get('concoursId');
        $oeuvreId = $request->request->get('oeuvreId');
        $note = (int) $request->request->get('note'); // Nom cohérent
    
        // Validez que la note est présente
        if ($note === null) {
            throw new \Exception("La note n'a pas été soumise correctement.");
        }
       

      
    
        // Note potentielle provenant du formulaire
     
        // Initialiser l'ID de l'utilisateur à 50 (à adapter selon votre logique)
        $userId = 50;
    
        // Récupérer l'objet Concours correspondant à partir de l'ID
        $concours = $entityManager->getRepository(Concours::class)->find($concoursId);
    
        if (!$concours) {
            // Redirection ou affichage de message d'erreur approprié
            $this->addFlash('error', 'Concours non trouvé.');
            return $this->redirectToRoute('nom_de_la_route_vers_la_page_d_accueil');
        }
    
        // Récupérer l'objet Oeuvreart correspondant à partir de l'ID
        $oeuvre = $entityManager->getReference(Oeuvreart::class, $oeuvreId);
    
        if (!$oeuvre) {
            // Redirection ou affichage de message d'erreur approprié
            $this->addFlash('error', 'Oeuvre non trouvée.');
            return $this->redirectToRoute('nom_de_la_route_vers_la_page_d_accueil');
        }
    
        // Vérifier si l'utilisateur a déjà voté pour cette œuvre dans ce concours
        $existingVote = $entityManager->getRepository(Vote::class)->findOneBy([
            'user' => $userId,
            'oeuvre' => $oeuvre,
            'concours' => $concours
        ]);
    
        if ($existingVote) {
            // Redirection ou affichage de message d'erreur approprié
            $this->addFlash('error', 'Vous avez déjà voté pour cette œuvre dans ce concours.');
            return $this->redirectToRoute('app_vote_index');
        }
    
        // Créer une nouvelle instance de l'entité Vote avec les données reçues
        $vote = new Vote();
        $vote->setConcours($concours);
        $vote->setOeuvre($oeuvre);
        $vote->setNote($note);
    
        // Récupérer l'utilisateur avec l'ID 50 depuis la base de données
        $user = $entityManager->getReference(Utilisateur::class, $userId);
    
        // Associer l'utilisateur à l'entité Vote
        $vote->setUser($user);
    
        try {
            // Sauvegarder le vote dans la base de données
            $entityManager->persist($vote);
            $entityManager->flush();
    
            // Redirection ou affichage de message de succès approprié
            $this->addFlash('success', 'Vote enregistré avec succès.');
            return $this->redirectToRoute('app_vote_index');
        } catch (\Exception $e) {
            // Redirection ou affichage de message d'erreur approprié
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'enregistrement du vote. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_vote_index');
        }
    }
    



    #[Route('/{id}', name: 'app_vote_delete', methods: ['POST'])]
    public function delete(Request $request, Vote $vote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_vote_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Vote $vote, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(VoteType::class, $vote);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Vote modifié avec succès.');

        return $this->redirectToRoute('app_vote_index');
    }

    return $this->render('vote/edit.html.twig', [
        'vote' => $vote,
        'form' => $form->createView(),
    ]);
}

}
