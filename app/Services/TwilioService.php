<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendWhatsAppMessage($to, $message)
    {
        // Sugestão: tratar exceptions do client e re-tentar com backoff em erros transitórios.
        // Benefício: aumenta resiliência contra falhas temporárias na API externa.
        // Sugestão: injetar o Client via constructor (interface) para facilitar mocking em testes.
        // Benefício: permite testes unitários sem chamar o Twilio real.
        try {
            return $this->twilio->messages->create($to, [
                'from' => config('services.twilio.whatsapp_from'),
                'body' => $message
            ]);
        } catch (\Throwable $e) {
            // Sugestão: logar o erro com contexto e opcionalmente rethrowar uma exception customizada.
            // Benefício: monitoramento mais claro e tratamento uniforme de erros externos.
            report($e);
            throw $e;
        }
    }
}
