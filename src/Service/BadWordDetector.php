<?php

namespace App\Service;

class BadWordDetector
{
    public function callBadWordApi(string $text): ?array
    {
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.apilayer.com/bad_words?censor_character=*",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: G6KGVSYGI0uLnaDjGOlw6uzJEwbGgE6a"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $text,
        ]);
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        if ($response) {
            return json_decode($response, true);
        }
    
        return null;
    }
}
