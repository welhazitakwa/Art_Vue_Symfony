<?php

namespace App\Controller;

use App\Repository\OeuvreartRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QRCodeController extends AbstractController
{
    #[Route('/q/r/code', name: 'app_q_r_code')]
    public function index(): Response
    {
        return $this->render('qr_code/index.html.twig', [
            'controller_name' => 'QRCodeController',
        ]);
    }

    
//---------QR CODE--------------------
    #[Route('/oeuvre/qr-code/{id}', name: 'pays_qr_code')]
    public function generateQRCode(int $id, OeuvreartRepository $oeuvreartRepository): Response
    {
        $oeuvreArt = $oeuvreartRepository->find($id); 
        $qrCodeContent = $oeuvreArt->getDescription();

        $builder = Builder::create()
        ->writer(new PngWriter())
        ->data($qrCodeContent)
        ->encoding(new Encoding('UTF-8'))
        ->size(200)
        ->margin(10)
        ->build();

    return new Response($builder->getString());
    }
}
