<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\TwilioService;
use Carbon\Carbon;

class ConfirmAppointments extends Command
{
    protected $signature = 'appointments:confirm';
    protected $description = 'Envia confirmações de agendamentos via WhatsApp usando Twilio';

    public function handle(TwilioService $twilio)
    {
        $amanha = Carbon::tomorrow()->startOfDay();
        $fim = $amanha->copy()->endOfDay();

        $appointments = Appointment::with(['user', 'service', 'barber'])
            ->whereBetween('start', [$amanha, $fim])
            ->get();

        foreach ($appointments as $appointment) {
            $data = Carbon::parse($appointment->start)->translatedFormat('d \d\e F \d\e Y');
            $hora = Carbon::parse($appointment->start)->format('H:i');

            $mensagem = "*Olá, {$appointment->user->name}!* 👋\n\n";
            $mensagem .= "Estamos passando para *confirmar seu agendamento* para amanhã!\n\n";
            $mensagem .= "📅 *Data:* {$data}\n";
            $mensagem .= "⏰ *Horário:* {$hora}\n";
            $mensagem .= "💇‍♂️ *Serviço:* {$appointment->service->name}\n";
            $mensagem .= "💰 *Preço:* R$ " . number_format($appointment->service->price, 2, ',', '.') . "\n";
            $mensagem .= "✂️ *Profissional:* {$appointment->barber->name}\n\n";
            $mensagem .= "Responda com *1* para confirmar ou *2* para cancelar.";

            $twilio->sendWhatsAppMessage(env('TWILIO_WHATSAPP_TO'), $mensagem);
        }

        $this->info("Mensagens de confirmação enviadas com sucesso.");
    }
}
