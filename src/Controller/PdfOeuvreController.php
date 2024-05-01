<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Oeuvreart;
use App\Repository\OeuvreartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfOeuvreController extends AbstractController
{
  
    #[Route('/pdf/oeuvre', name: 'app_pdf_oeuvre', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , OeuvreartRepository $oeuvreartsRepository): Response
    {
        
            $categories = $entityManager->getRepository(Categorie::class)->findAll();
            

        return $this->render('oeuvreart/templatePDF.html.twig', [
            'oeuvrearts' => $oeuvreartsRepository->trie_decroissant_date(),
            'categories' => $categories,
           
            
        ]);
    }

    #[Route('/pdf', name: 'app_pdf')]
    public function generatePdf(EntityManagerInterface $entityManager, OeuvreartRepository $oeuvreartsRepository): Response
    {
        // Récupérer les données des œuvres d'art à inclure dans le PDF
        $oeuvrearts = $oeuvreartsRepository->trie_decroissant_date();
    
        // Créer une instance de Dompdf
        $dompdf = new Dompdf();
    
        // Générer le contenu HTML du PDF en utilisant le template Twig
        $html = $this->renderView('oeuvreart/templatePDF.html.twig', [
            'oeuvrearts' => $oeuvrearts,
        ]);
    
        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
    
        // Rendre le PDF
        $dompdf->render();
    
        // Retourner une réponse avec le contenu du PDF
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="example.pdf"',
        ]);
    }
    



}
