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
        $date = Carbon::now()->addDay();
        
        $users = Appointment::query()
            ->with(['user', 'service', 'barber'])
            ->whereDate('start',$date)
            ->get()
            ->groupBy(function ($appointment){
                return $appointment->user->id;
            });

        foreach($users as $user)
        {
            $message = "*Olá, {$user[0]->user->name}!* 👋\n";
            $message .= "Estamos passando para *confirmar seu agendamento* para amanhã!\n\n";
            
            foreach($user as $index => $appointment)
            {
                $data = Carbon::parse($appointment->start)->translatedFormat('d \d\e F \d\e Y');
                $hora = Carbon::parse($appointment->start)->format('H:i');
                $service_number = $index + 1;

                $message .= "💇‍♂️ *Serviço {$service_number}:* {$appointment->service->name}\n";
                $message .= "📅 *Data:* {$data}\n";
                $message .= "⏰ *Horário:* {$hora}\n";
                $message .= "✂️ *Profissional:* {$appointment->barber->name}\n";
                $message .= "\n─────────────────────────\n\n";
            }

            $total = $this->get_total_price($user);
            $message .= "🧾 *Total:* _R$ {$total}_\n\n";
            $message .= "Responda com *1* para confirmar ou *2* para cancelar.\n";
            $message .= "🙏 Aguardamos seu retorno! Até breve. ✂️";

            $to = preg_replace('/\D/', '', $user[0]->user->phone);
            $to = 'whatsapp:+55' . preg_replace('/^(\d{2})9/', '$1', $to);
            
            $twilio->sendWhatsAppMessage($to, $message);
        }
        $this->info("Mensagens de confirmação enviadas com sucesso.");
    }

    public function get_total_price($user)
    {
        $price = 0;

        foreach ($user as $appointment) {
            $price += $appointment->service->price;
        }

        return number_format($price, 2, ',', '.'); // Ex: 1500.5 → 1.500,50
    }
}
