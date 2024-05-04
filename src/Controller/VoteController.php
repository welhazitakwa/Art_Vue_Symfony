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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;



#[Route('/vote')]
class VoteController extends AbstractController

{

  /*   #[Route('/', name: 'app_vote_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , SessionInterface $session): Response
    {
       
        $userId  = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));

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
    public function new(Request $request, EntityManagerInterface $entityManager , SessionInterface $session): Response
    {
        // Récupérer les données de la requête
      
      
        $concoursId = $request->request->get('concoursId');
        $oeuvreId = $request->request->get('oeuvreId');
        $note = (int) $request->request->get('note'); // Nom cohérent
        $userId  = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));
    
        // Validez que la note est présente
        if ($note === null) {
            throw new \Exception("La note n'a pas été soumise correctement.");
        }
       

      
    
        // Note potentielle provenant du formulaire
     
        // Initialiser l'ID de l'utilisateur à 50 (à adapter selon votre logique)
       // $userId = 50;
    
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
    
*/

#[Route('/', name: 'app_vote_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    // Récupérer l'ID de l'utilisateur à partir de la session
    $userId = $session->get('user_id');

    // Valider si l'ID de l'utilisateur est présent dans la session
    if (!$userId) {
        throw new \Exception("ID de l'utilisateur non trouvé dans la session.");
    }

    // Récupérer l'objet Utilisateur correspondant à partir de l'ID
    $user = $entityManager->getRepository(Utilisateur::class)->find($userId);

    // Valider si l'utilisateur existe dans la base de données
    if (!$user) {
        throw new \Exception("Utilisateur non trouvé dans la base de données.");
    }

    // Récupérer les votes de l'utilisateur avec l'ID récupéré de la session
    $votes = $entityManager
        ->getRepository(Vote::class)
        ->findBy(['user' => $user]);

    return $this->render('vote/index.html.twig', [
        'votes' => $votes,
    ]);
}

#[Route('/new', name: 'app_vote_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    // Récupérer l'ID de l'utilisateur à partir de la session
    $userId = $session->get('user_id');

    // Valider si l'ID de l'utilisateur est présent dans la session
    if (!$userId) {
        throw new \Exception("ID de l'utilisateur non trouvé dans la session.");
    }

    // Récupérer l'objet Utilisateur correspondant à partir de l'ID
    $user = $entityManager->getRepository(Utilisateur::class)->find($userId);

    // Valider si l'utilisateur existe dans la base de données
    if (!$user) {
        throw new \Exception("Utilisateur non trouvé dans la base de données.");
    }

    // Récupérer les données de la requête
    $concoursId = $request->request->get('concoursId');
    $oeuvreId = $request->request->get('oeuvreId');
    $note = (int) $request->request->get('note'); // Nom cohérent

    // Validez que la note est présente
    if ($note === null) {
        throw new \Exception("La note n'a pas été soumise correctement.");
    }

    // Récupérer l'objet Concours correspondant à partir de l'ID
    $concours = $entityManager->getRepository(Concours::class)->find($concoursId);

    // Valider si le concours existe
    if (!$concours) {
        throw new \Exception("Concours non trouvé.");
    }

    // Récupérer l'objet Oeuvreart correspondant à partir de l'ID
    $oeuvre = $entityManager->getReference(Oeuvreart::class, $oeuvreId);

    // Valider si l'oeuvre existe
    if (!$oeuvre) {
        throw new \Exception("Oeuvre non trouvée.");
    }

    // Vérifier si l'utilisateur a déjà voté pour cette œuvre dans ce concours
    $existingVote = $entityManager->getRepository(Vote::class)->findOneBy([
        'user' => $user,
        'oeuvre' => $oeuvre,
        'concours' => $concours
    ]);

    // Si un vote existe déjà, afficher un message d'erreur et rediriger
    if ($existingVote) {
        $this->addFlash('error', 'Vous avez déjà voté pour cette œuvre dans ce concours.');
        return $this->redirectToRoute('app_vote_index');
    }

    // Créer une nouvelle instance de l'entité Vote avec les données reçues
    $vote = new Vote();
    $vote->setConcours($concours);
    $vote->setOeuvre($oeuvre);
    $vote->setNote($note);
    $vote->setUser($user);

    try {
        // Sauvegarder le vote dans la base de données
        $entityManager->persist($vote);
        $entityManager->flush();

        // Afficher un message de succès et rediriger
        $this->addFlash('success', 'Vote enregistré avec succès.');
        return $this->redirectToRoute('app_vote_index');
    } catch (\Exception $e) {
        // En cas d'erreur, afficher un message d'erreur et rediriger
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
