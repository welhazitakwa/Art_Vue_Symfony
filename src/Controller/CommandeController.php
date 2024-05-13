<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Livraison;
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
//use Twilio\Rest\Client;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Utilisateur;
use Psr\Log\LoggerInterface; 
use App\Controller\attributes;
#[Route('/commande')]
class CommandeController extends AbstractController
{
  

    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $commandes = $commandeRepository->findAll();
        $commandes = $paginator->paginate(
            $commandes,
            $request->query->getInt('page', 1),
            4,
        );
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,

        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande,EntityManagerInterface $entityManager,Request $request): Response
    {           

          $cmd=$entityManager->getRepository(Commande::class)->find($request->attributes->get('id'));
        $pan=$cmd->getPanier();
        return $this->render('commande/show.html.twig', [
            'commande' => $cmd,
            'pan' => $pan,


        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        $commande=$entityManager->getRepository(Commande::class)->find($request->attributes->get('id'));
        $pan=$commande->getPanier();       

        $form = $this->createFormBuilder($commande)
        ->add('etat', ChoiceType::class, [
            'choices' => [
                'Terminée' => 'Terminée',
            ],
        ])
        ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $client = $commande->getPanier()->getClient();


        // Si l'état de la commande est "Terminée", créer une livraison
        if ($commande->getEtat() === 'Terminée') {
            $livraison = new Livraison();
            $livraison->setCommande($commande);
            $livraison->setAdresse($commande->getPanier()->getClient()->getAdresse());
            $livraison->setFrais(10); // Frais de livraison fixe
            $livraison->setTotal($commande->getMontant() + 10); // Total = Montant de la commande + frais de livraison
            $entityManager->persist($livraison);
            $entityManager->flush();
            //mail
//$recipientEmail = $client->getEmail(); // Récupérer l'e-mail du client

  // Créer l'e-mail à envoyer
  $email = (new Email())
  ->from('artvuecontact@gmail.com') // Adresse de l'expéditeur
  ->to($client->getEmail()) // Adresse du client
  ->subject('Fidélité!')
  ->text("Votre commande est terminée. La livraison est en cours");

// Envoyer l'e-mail et gérer les exceptions
    $mailer->send($email);
  



}
 

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('commande/edit.html.twig', [
        'commande' => $commande,
        'form' => $form,
    ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }



    //affichage client
  
#[Route('/panier/afficher', name: 'panier_afficher_commandes')]
public function listerCommandesPanier(Request $request,EntityManagerInterface $entityManager, PaginatorInterface $paginator, SessionInterface $session): Response
{

      // Récupérer l'utilisateur actuel à partir de la session
      $userId = $session->get('user_id');

      // Récupérer le panier de l'utilisateur actuel
      $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $userId]);    
   
    // Vérifier si le panier existe
    if (!$panier) {
        throw $this->createNotFoundException('Le panier demandé n\'existe pas.');
    }
        
    $etat = $request->query->get('etat');

    // Récupérer toutes les commandes liées à ce panier
    $commandes = $panier->getCommandes();
  

    // Filtrer les commandes par état si un filtre est spécifié
    if ($etat === 'envoyé') {
        $commandes = $commandes->filter(function (Commande $commande) {
            return $commande->getEtat() === 'envoyé';
        });
    } elseif ($etat === 'Terminée') {
        $commandes = $commandes->filter(function (Commande $commande) {
            return $commande->getEtat() === 'Terminée';
        });
    }
    $commandes = $paginator->paginate(
        $commandes,
        $request->query->getInt('page', 1),
        6,
    );
    return $this->render('commande/listeCommandeClient.html.twig', [
        'commandes' => $commandes,
    ]);
}

#[Route('/commande/supprimer', name: 'supprimer_commande')]
public function supprimerCommande(Request $request): Response
{
    // Récupérer l'ID de la commande à supprimer à partir des données POST
    $data = json_decode($request->getContent(), true);
    $commandeId = $data['commandeId'];

    // Récupérer la commande à supprimer depuis la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $commande = $entityManager->getRepository(Commande::class)->find($commandeId);

    // Vérifier si la commande existe
    if ($commande) {
        // Supprimer la commande
        $entityManager->remove($commande);
        $entityManager->flush();
    }

    // Répondre avec une réponse vide (200 OK)
    return new Response('', Response::HTTP_OK);
}
 

}