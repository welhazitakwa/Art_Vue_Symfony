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
use Twilio\Rest\Client;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
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

       

        // Si l'état de la commande est "Terminée", créer une livraison
        if ($commande->getEtat() === 'Terminée') {
            $livraison = new Livraison();
            $livraison->setCommande($commande);
            $livraison->setAdresse($commande->getPanier()->getClient()->getAdresse());
            $livraison->setFrais(10); // Frais de livraison fixe
            $livraison->setTotal($commande->getMontant() + 10); // Total = Montant de la commande + frais de livraison
            $entityManager->persist($livraison);
            $entityManager->flush();
 //sms
$twilioSid = "AC470844d0266cf005c021823127fd8530";
$twilioToken = "706c7dd7eb86174b9b3cc072da6365ca";
$twilioPhoneNumber = "+13343397109";
$phoneNumber = '+21625721357'; // Remplacez par le numéro de téléphone réel de votre base de données
try {
    $client = new Client($twilioSid, $twilioToken);
    $client->messages->create(
        $phoneNumber,
        [
            'from' => $twilioPhoneNumber,
            'body' => 'Votre commande est terminée. La livraison est en cours.'
        ]
    );
} catch (\Exception $e) {
    // Gérer l'exception ici
    $errorMessage = $e->getMessage();
    $this->addFlash('error', 'Erreur lors de l\'envoi du SMS : ' . $errorMessage);
}
//mail
// Adresse e-mail statique à laquelle envoyer les confirmations de commande
$recipientEmail = 'oumeyma.benkram@esprit.tn';

  // Créer l'e-mail à envoyer
  $email = (new Email())
  ->from('oumeyma.benkram@esprit.tn') // Adresse de l'expéditeur
  ->to($recipientEmail) // Adresse du client
  ->subject('Fidélité!')
  ->text("Votre commande est terminée. La livraison est en cours");

// Envoyer l'e-mail et gérer les exceptions
try {
    $mailer->send($email);
  } catch (\Exception $e) {
  return new Response("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage(), 500);
}
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
public function listerCommandesPanier(Request $request,EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
{
    $panierId = 43; // Remplacez 43 par l'ID du panier que vous souhaitez afficher
    
    // Récupérer le panier à partir de l'ID
    $panier = $entityManager->getRepository(Panier::class)->find($panierId);
    
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
        4,
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