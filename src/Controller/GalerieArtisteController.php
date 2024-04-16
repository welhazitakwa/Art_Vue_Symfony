<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OeuvreartRepository;
use App\Entity\Oeuvreart;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Form\OeuvreartType;
use App\Entity\Categorie;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    
    $oeuvreart = new Oeuvreart();
    $userId = 14;
    $user = $entityManager->getRepository(Utilisateur::class)->find($userId);
    $oeuvreart->setIdArtiste($user);
    $form = $this->createForm(OeuvreartType::class, $oeuvreart);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $oeuvreart->setImage($fileName);
        $entityManager->persist($oeuvreart);
        $entityManager->flush();

        // Redirection vers la page d'index des œuvres d'art
        return $this->redirectToRoute('app_oeuvreart_index', [], Response::HTTP_SEE_OTHER);
    }

    // Affichage du formulaire
    return $this->renderForm('oeuvreart/new.html.twig', [
        'oeuvreart' => $oeuvreart,
        'form' => $form,
    ]);
}


}
