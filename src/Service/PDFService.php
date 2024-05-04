<?php

namespace App\Service;

class PDFService
{


    public function generatePdf($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/url_to_pdf?url=" . urlencode($url),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: G6KGVSYGI0uLnaDjGOlw6uzJEwbGgE6a"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
