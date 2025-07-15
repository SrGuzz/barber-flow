<?php

use App\Mail\Cancel;
use App\Mail\Confirm;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\TwiML\MessagingResponse;

Route::post('/webhook/whatsapp', function (Request $request) {
    $from = $request->input('From');               
    $body = trim($request->input('Body'));         

    Log::info("Recebido de $from: $body");

    $phone = str_replace('whatsapp:+55', '', $from);
    
    $ddd     = substr($phone, 0, 2);
    $resto   = substr($phone, 2);

    $phone = "($ddd) 9$resto";

    $appointments = Appointment::whereHas('user', function ($query) use ($phone) {
        $query->where('phone', $phone);
    })
    ->whereNull('confirmed_at')
    ->latest()
    ->get();

    $msg = new MessagingResponse();

    if ($appointments) {
        if ($body === '1') 
        {
            foreach($appointments as $appointment)
            {
                $appointment->update(['confirmed_at' => now()]);
                Mail::to($appointment->user->email)->send(new Confirm($appointment));
            }
            

            $msg->message(
                "*✅ Agendamento Confirmado!*\n\n" .
                "Seu horário foi confirmado com sucesso! ✨\n\n" .
                "Estamos te esperando! 😄\nSe tiver qualquer dúvida, é só nos chamar.\n\n" .
                "*Obrigado por escolher nossos serviços!* 💈"
            );
        } 
        elseif ($body === '2') 
        {
            foreach($appointments as $appointment)
            {
                $appointment->delete(); 
                Mail::to($appointment->user->email)->send(new Cancel($appointment));
            }

            $msg->message(
                "*❌ Agendamentos Cancelados!*\n\n" .
                "Recebemos sua solicitação de cancelamento para os serviços a serem confirmados e tudo foi atualizado com sucesso. 🗓️\n\n" .
                "Esperamos poder te atender em uma próxima oportunidade! 😊\n\n" .
                "Se quiser reagendar ou tirar alguma dúvida, é só nos chamar. 📲\n\n" .
                "*Agradecemos por considerar nossos serviços!* 💈"
            );
        } 
        else 
        {
            $msg->message("🤖: Responda com *1* para confirmar ou *2* para cancelar.");
        }
    } else {
        $msg->message("⚠️ Nenhum agendamento pendente encontrado.");
    }

    return response($msg, 200)->header('Content-Type', 'text/xml');
});