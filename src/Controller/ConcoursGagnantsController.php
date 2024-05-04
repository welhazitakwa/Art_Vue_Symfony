<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Entity\OeuvreConcours;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface; 
use Twilio\Rest\Client; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use Knp\Snappy\Pdf;



#[Route('/concours_gagnants')]
class ConcoursGagnantsController extends AbstractController
{

  /*  #[Route('/notifications', name: 'app_concours_notifications', methods: ['GET'])]
    public function getNotifications(EntityManagerInterface $entityManager): JsonResponse
    {
        $today = new DateTime();
        $concours = $entityManager->getRepository(Concours::class)->findBy([
            'dateFin' => $today->format('Y-m-d'),
        ]);

        $notifications = [];

        foreach ($concours as $concour) {
            $notifications[] = [
                'id' => $concour->getId(),
                'titre' => $concour->getTitre(),
                'dateFin' => $concour->getDateFin(),
            ];
        }

        return new JsonResponse($notifications);
    }*/


    #[Route('/', name: 'app_concours_gagnants', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $concours = $entityManager->getRepository(Concours::class)->findAll();

        $gagnants = [];

        foreach ($concours as $concour) {
            $oeuvreConcours = $entityManager
                ->getRepository(OeuvreConcours::class)
                ->findBy(['idConcours' => $concour]);

            $votesParOeuvre = [];
            foreach ($oeuvreConcours as $oc) {
                $oeuvre = $oc->getIdOeuvre();
                $nombreDeVotes = $entityManager
                    ->getRepository(Vote::class)
                    ->count(['oeuvre' => $oeuvre, 'concours' => $concour]);

                $votesParOeuvre[] = [
                    'oeuvre' => $oeuvre,
                    'votes' => $nombreDeVotes,
                ];
            }

            usort($votesParOeuvre, function ($a, $b) {
                return $b['votes'] - $a['votes'];
            });

            $top3 = array_slice($votesParOeuvre, 0, 3);

            $gagnants[$concour->getId()] = [
                'concours' => $concour,
                'oeuvres' => $top3,
            ];
        }

        return $this->render('concours_gagnants/index.html.twig', [
            'gagnants' => $gagnants,
            
        ]);

      
    }

    
    #[Route('/envoyer_sms/{concoursId}', name: 'app_concours_gagnants_envoyer_sms', methods: ['POST'])]
    public function envoyerSMS(int $concoursId, LoggerInterface $logger): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $concours = $entityManager->getRepository(Concours::class)->find($concoursId);

    if (!$concours) {
        return new Response("Concours introuvable.", 404);
    }

    $oeuvreConcours = $entityManager->getRepository(OeuvreConcours::class)->findBy(['idConcours' => $concours]);
    if (count($oeuvreConcours) === 0) {
        return new Response("Pas d'œuvres pour ce concours.", 404);
    }

    // Obtenez le premier gagnant
    $votesParOeuvre = [];
    foreach ($oeuvreConcours as $oc) {
        $oeuvre = $oc->getIdOeuvre();
        $nombreDeVotes = $entityManager->getRepository(Vote::class)->count(['oeuvre' => $oeuvre, 'concours' => $concours]);
        $votesParOeuvre[] = ['oeuvre' => $oeuvre, 'votes' => $nombreDeVotes];
    }

    usort($votesParOeuvre, function ($a, $b) {
        return $b['votes'] - $a['votes'];
    });

    $gagnant = $votesParOeuvre[0]['oeuvre'];
    $artiste = $gagnant->getIdArtiste();

    // Ajoutez +216 et supprimez le zéro initial
    $numtel = '+216' . ltrim($artiste->getNumtel(), '0'); 

    $logger->info("Numéro de téléphone formaté : " . $numtel);

    // Configuration de Twilio (ou autre service SMS)
    $twilioSid =  $_ENV['TWILIO_ACCOUNT_SID'];
    $twilioAuthToken = $_ENV['TWILIO_AUTH_TOKEN'];
    $twilioFromNumber = $_ENV['TWILIO_PHONE_NUMBER'];

    $client = new Client($twilioSid, $twilioAuthToken);

    try {
        $client->messages->create(
            $numtel, // Numéro de téléphone formaté avec le code de pays
            [
                'from' => $twilioFromNumber,
                'body' => "Félicitations, vous avez remporté le concours : {$concours->getTitre()}."
            ]
        );
        $logger->info("SMS envoyé à : " . $numtel);
    } catch (\Exception $e) {
        $logger->error("Erreur lors de l'envoi du SMS : " . $e->getMessage());
        return new Response("Erreur lors de l'envoi du SMS : " . $e->getMessage(), 500);
    }

    return new Response("SMS envoyé avec succès.", 200);
}




    
#[Route('/envoyer_email/{concoursId}', name: 'app_concours_gagnants_envoyer_email', methods: ['POST'])]
    public function envoyerEmail(
        int $concoursId,
        LoggerInterface $logger,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    ): Response {
        // Rechercher le concours par ID
        $concours = $entityManager->getRepository(Concours::class)->find($concoursId);

        if (!$concours) {
            return new Response("Concours introuvable.", 404);
        }

        // Rechercher les œuvres associées au concours
        $oeuvreConcours = $entityManager->getRepository(OeuvreConcours::class)->findBy(['idConcours' => $concours]);
        if (count($oeuvreConcours) === 0) {
            return new Response("Pas d'œuvres pour ce concours.", 404);
        }

        // Identifier le gagnant en fonction du nombre de votes
        $votesParOeuvre = [];
        foreach ($oeuvreConcours as $oc) {
            $oeuvre = $oc->getIdOeuvre();
            $nombreDeVotes = $entityManager->getRepository(Vote::class)->count(['oeuvre' => $oeuvre, 'concours' => $concours]);
            $votesParOeuvre[] = ['oeuvre' => $oeuvre, 'votes' => $nombreDeVotes];
        }

        // Trier les œuvres par nombre de votes décroissant
        usort($votesParOeuvre, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });

        // Prendre l'œuvre avec le plus grand nombre de votes
        $gagnant = $votesParOeuvre[0]['oeuvre'];
        $artiste = $gagnant->getIdArtiste();

        // Créer l'e-mail à envoyer
        $email = (new Email())
            ->from('artvuecontact@gmail.com') // Adresse de l'expéditeur
            ->to($artiste->getEmail()) // Adresse du gagnant
            ->subject('Félicitations pour avoir gagné le concours !')
            ->text("Félicitations, vous avez remporté le concours : {$concours->getTitre()}. Nombre de votes : {$votesParOeuvre[0]['votes']}.");

        // Envoyer l'e-mail et gérer les exceptions
        try {
            $mailer->send($email);
            $logger->info("E-mail envoyé à : " . $artiste->getEmail());
            return new Response("E-mail envoyé avec succès.", 200);
        } catch (\Exception $e) {
            $logger->error("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage());
            return new Response("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage(), 500);
        }
    }

  
    #[Route('/generer_pdf/{concoursId}/{artisteId}', name: 'app_concours_gagnants_generer_pdf', methods: ['GET'])]
    public function genererPDF(int $concoursId, int $artisteId, Request $request, Pdf $knpSnappyPdf): Response
    {
        // Récupérer les données du gagnant du concours depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
    
        // Recherche du concours par son ID
        $concours = $entityManager->getRepository(Concours::class)->find($concoursId);
    
        if (!$concours) {
            return new Response("Concours introuvable.", 404);
        }
    
        // Recherche des œuvres associées au concours
        $oeuvreConcours = $entityManager->getRepository(OeuvreConcours::class)->findBy(['idConcours' => $concours]);
        if (count($oeuvreConcours) === 0) {
            return new Response("Pas d'œuvres pour ce concours.", 404);
        }
    
        // Identifier le gagnant en fonction du nombre de votes
        $votesParOeuvre = [];
        foreach ($oeuvreConcours as $oc) {
            $oeuvre = $oc->getIdOeuvre();
            $nombreDeVotes = $entityManager->getRepository(Vote::class)->count(['oeuvre' => $oeuvre, 'concours' => $concours]);
            $votesParOeuvre[] = ['oeuvre' => $oeuvre, 'votes' => $nombreDeVotes];
        }
    
        // Trier les œuvres par nombre de votes décroissant
        usort($votesParOeuvre, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });
    
        // Prendre l'œuvre avec le plus grand nombre de votes (le gagnant)
        $gagnant = $votesParOeuvre[0]['oeuvre'];
        $artiste = $gagnant->getIdArtiste();
    
        // Générer le contenu du PDF
        $pdfContent = $this->renderView('concours_gagnants/attestation.html.twig', [
            'concours' => $concours,
            'artiste' => $artiste,
        ]);
    
        // Générer le PDF à partir du contenu HTML
        $pdf =  $knpSnappyPdf->getOutputFromHtml($pdfContent);
    
        // Renvoyer la réponse PDF au navigateur
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="attestation.pdf"'
        ]);}

}
