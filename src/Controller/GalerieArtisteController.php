<?php

namespace App\Controller;

use App\Form\ArtisteOeuvreType;
use App\Form\EditOeuvreArtisteType;
use App\Form\EditOeuvreType;
use App\Service\TwilioSmsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BadWordDetector;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OeuvreartRepository;
use App\Entity\Oeuvreart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Utilisateur;
use App\Form\OeuvreartType;
use App\Entity\Categorie;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twilio\Rest\Client;

class GalerieArtisteController extends AbstractController
{
    #[Route('/galerie/artiste/Oeuvre', name: 'app_galerieOeuvre_artiste')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager, OeuvreartRepository $oeuvreartRepository): Response
{
    // Récupérer l'artiste d'ID 14
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));
    
    // Récupérer les œuvres de l'artiste d'ID 14
    $oeuvrearts = $oeuvreartRepository->findBy(['idArtiste' => $utilisateur]);
    
    // Récupérer toutes les catégories
    $categories = $entityManager->getRepository(Categorie::class)->findAll();
    
    return $this->render('galerie/galerieArtiste.html.twig', [
        'categories' => $categories,
        'oeuvrearts' => $oeuvrearts,
    ]);
}
#[Route('/artiste/{idoeuvreart}', name: 'app_oeuvreart_deleteArtiste', methods: ['POST'])]
public function delete(Request $request, Oeuvreart $oeuvreart, EntityManagerInterface $entityManager): Response
{
    $oeuvreart=$entityManager->getRepository(Oeuvreart::class)->find($request->attributes->get('idoeuvreart'));

    if ($this->isCsrfTokenValid('delete'.$oeuvreart->getIdoeuvreart(), $request->request->get('_token'))) {
        $entityManager->remove($oeuvreart);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_galerieOeuvre_artiste', [], Response::HTTP_SEE_OTHER);
} 


#[Route('/galerie/artiste/OeuvreByCategorie/{idcategorie}', name: 'app_galerieOeuvre_artisteByCategorie')]
    public function ArtisteByCategorie(SessionInterface $session, int $idcategorie, EntityManagerInterface $entityManager, OeuvreartRepository $oeuvreartRepository): Response
    {
        // Récupérer l'artiste d'ID 14
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));

        // Récupérer la catégorie
        $categorie = $entityManager->getRepository(Categorie::class)->find($idcategorie);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            throw $this->createNotFoundException('Aucune catégorie trouvée pour l\'ID '.$idcategorie);
        }

        // Récupérer les œuvres de l'artiste d'ID 14 par catégorie
        $oeuvrearts = $oeuvreartRepository->findBy(['idArtiste' => $utilisateur, 'idCategorie' => $categorie]);

        // Récupérer toutes les catégories
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        return $this->render('galerie/galerieArtiste.html.twig', [
            'categories' => $categories,
            'oeuvrearts' => $oeuvrearts,
        ]);
    }


    #[Route('galerie/new', name: 'app_oeuvreart_newGalerie', methods: ['GET', 'POST'])]
    public function new(SessionInterface $session, Request $request,TwilioSmsService $smsService, BadWordDetector $badWordDetector, EntityManagerInterface $entityManager): Response
    {
        $oeuvreart = new Oeuvreart();
        $user = $entityManager->getRepository(Utilisateur::class)->find($session->get('user_id'));
        $oeuvreart->setIdArtiste($user);
        
        $form = $this->createForm(ArtisteOeuvreType::class, $oeuvreart);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $oeuvreart->setImage($fileName);
            
            // Vérification des mots interdits dans le titre
            $titreText = $oeuvreart->getTitre();
            $apiResponseTitre = $badWordDetector->callBadWordApi($titreText);
            if ($apiResponseTitre && isset($apiResponseTitre['censored_content'])) {
                $oeuvreart->setTitre($apiResponseTitre['censored_content']);
            } else {
                $oeuvreart->setTitre($titreText);
            }
    
            // Vérification des mots interdits dans la description
            $descriptionText = $oeuvreart->getDescription();
            $apiResponseDescription = $badWordDetector->callBadWordApi($descriptionText);
            if ($apiResponseDescription && isset($apiResponseDescription['censored_content'])) {
                $oeuvreart->setDescription($apiResponseDescription['censored_content']);
            } else {
                $oeuvreart->setDescription($descriptionText);
            }
    
            $entityManager->persist($oeuvreart);
            $entityManager->flush();

            // Envoi du SMS
            $account_sid = $_ENV['TWILIO_ACCOUNT_SID'];
            $auth_token = $_ENV['TWILIO_AUTH_TOKEN'];
            $twilio_number = $_ENV['TWILIO_PHONE_NUMBER'];
            $client = new Client($account_sid, $auth_token);
    
        
            $recipient_phone_number = '+21692404237';  
    
            $client->messages->create(
                $recipient_phone_number,
                [
                    'from' => $twilio_number,
                    'body' => 'Une nouvelle oeuvre a été ajoutée : " ' . $oeuvreart->getTitre() . '" Venez la découvrir sur ArtVue !',
                ]
            );
            
            // Redirection vers la page d'index des œuvres d'art
            return $this->redirectToRoute('app_galerieOeuvre_artiste', [], Response::HTTP_SEE_OTHER);
        }
    
        // Affichage du formulaire
        return $this->renderForm('galerie/AddGalerieArtiste.html.twig', [
            'oeuvreart' => $oeuvreart,
            'form' => $form,
        ]);
    }
    

#[Route('/{idoeuvreart}/editOeuvre', name: 'app_oeuvreart_editArtiste', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvreart $oeuvreart, EntityManagerInterface $entityManager): Response
    {
        $oeuvreart=$entityManager->getRepository(Oeuvreart::class)->find($request->attributes->get('idoeuvreart'));

        $form = $this->createForm(EditOeuvreArtisteType::class, $oeuvreart, [
            'attr' => ['enctype' => 'multipart/form-data'],
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si un fichier d'image est téléchargé
            $file = $form->get('image')->getData();
            if ($file instanceof UploadedFile) {
                // Génère un nom de fichier unique
                $fileName = uniqid().'.'.$file->guessExtension();
                // Déplace le fichier vers le répertoire souhaité
                $file->move($this->getParameter('images_directorys'), $fileName);
                // Met à jour le nom du fichier dans l'entité
                $oeuvreart->setImage($fileName);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_galerieOeuvre_artiste', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('galerie/editOeuvreArtiste.html.twig', [
            'oeuvreart' => $oeuvreart,
            'form' => $form,
        ]);
    }
   

}
