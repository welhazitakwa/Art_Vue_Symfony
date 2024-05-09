<?php
   // src/Service/TwilioSmsService.php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private $sid;
    private $token;
    private $twilio;

    public function __construct(string $sid = null, string $token = null)
    {
        $this->sid = $sid;
        $this->token = $token;
        if ($this->sid && $this->token) {
            $this->twilio = new Client($this->sid, $this->token);
        }
    }

    public function sendSms(string $to, string $from, string $body): void
    {
        if (!$this->twilio) {
            // Gérer l'erreur, par exemple en lançant une exception
            throw new \Exception("Les identifiants Twilio ne sont pas définis.");
        }
        $message = $this->twilio->messages
            ->create($to, [
                "from" => $from,
                "body" => $body,
            ]);
    }
}
