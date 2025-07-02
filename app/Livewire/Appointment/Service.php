<?php

namespace App\Livewire\Appointment;

use App\Mail\Appointment as MailAppointment;
use App\Models\Appointment;
use App\Models\Service as ModelsService;
use App\Models\User;
use App\Services\TwilioService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Service extends Component
{
    use Toast;

    public $modal_service = false;
    public $open_barbers = false;

    public $services = [];
    public $date_config = [];
    public $hours = [];

    public $date;
    public $hour;
    public $barber;
    public $actual_hour;
    public $barbers = [];

    public function render()
    {
        return view('livewire.appointment.service');
    }

    #[On('service::open')]
    public function show_modal($id)
    {
        if(!auth()->user()->phone)
        {
            $this->warning('Conclua o cadastro!', 'Cadastre um número de telefone antes de continuar!');
            return;
        } 

        $this->reset();
        Carbon::setLocale('pt-BR');

        $this->services[] = ModelsService::findOrFail($id);
        $this->modal_service = true;
        $this->date = Carbon::now()->addDay();

        $this->date_config = [
            'locale' => 'pt',
            'altInput' => true,
            'altFormat' => 'j F, Y',
            'minDate' => $this->date->year . '-' . $this->date->month . '-' . $this->date->day,
            'maxDate' => $this->date->year . '-' . $this->date->month + 1 . '-' . $this->date->copy()->endOfMonth()->day,
            'disable' => $this->get_sunday(),
        ];
        
        

        $this->hours = $this->get_hours($this->date);
        $this->hour = $this->hours[0]['id'];
        $this->actual_hour = $this->hour;
        $this->barber = $this->hours[0]['barbers'][0];
    }

    private function get_sunday()
    {
        $hoje = Carbon::now();

        // Início: 1º dia do mês atual
        $inicio = $hoje->copy()->startOfMonth()->startOfDay();

        // Fim: mesmo dia do mês seguinte
        $fim = $hoje->copy()->addMonthNoOverflow()->startOfDay();

        // Geração do período dia a dia
        $periodo = CarbonPeriod::create($inicio, $fim);

        // Filtra somente domingos
        $domingos = [];
        foreach ($periodo as $data) {
            if ($data->isSunday()) {
                $domingos[] = $data->format('Y-m-d');
            }
        }
        return $domingos;
    }

    private function get_hours($date)
    {
        $start = $date->copy()->setTime(8, 30);
        $end = $date->copy()->setTime(20, 55);

        $termino = $start->copy();
        $duration = Carbon::now()->setTime(0,0,0);
        foreach ($this->services as $service) {
            list($horas, $minutos) = explode(':', $service->duration);
            $termino->addHours((int) $horas)->addMinutes((int) $minutos);
            $duration->addHours((int) $horas)->addMinutes((int) $minutos);
        }
        $hours = [];

        $barbers = User::where('access_level', 1)->get();
        
        while($termino <= $end)
        {
            $conflitos = $this->haConflitoDeHorario($start, $termino);

            if(count($conflitos) >= $barbers->count())
            {
                while(count($this->haConflitoDeHorario($start, $termino)) >= $barbers->count())
                {
                    $start = $start->addMinutes(5);
                    $termino = $termino->addMinutes(5);
                }
            }
            else if(count($conflitos) < $barbers->count() && count($conflitos) > 0)
            {
                $ids = [];

                foreach($conflitos as $appointment)
                {
                    $ids[] = $appointment->barber->id;
                }

                $hours[] = [
                    'id' => $start->copy()->format('H:i'),
                    'name' => $start->copy()->format('H:i'),
                    'barbers' => User::whereNotIn('id', $ids)->where('access_level', 1)->get()->toArray(),
                ];

                $start = $start->addMinutes(5);
                $termino = $termino->addMinutes(5);
            }
            else
            {
                $hours[] = [
                    'id' => $start->copy()->format('H:i'),
                    'name' => $start->copy()->format('H:i'),
                    'barbers' => $barbers->toArray(),
                ];
                $start = $start->addMinutes(5);
                $termino = $termino->addMinutes(5);
            }
        }

        foreach ($hours as &$hour) {
            if (isset($hour['barbers']) && is_array($hour['barbers'])) {
                array_unshift($hour['barbers'], [
                    "id" => null,
                    "name" => "Sem preferência",
                    "disponible" => true
                ]);
            }
        }

        return $hours;
    }

    private function haConflitoDeHorario($start, $termino)
    {
        // Verifica se há algum agendamento entre o intervalo [$start, $termino]
        $conflito = Appointment::where('start', '<=', $termino)->where('end', '>=', $start)->get();

        return $conflito;
    }

    public function updatedDate($value)
    {
        $this->hours = $this->get_hours($value->setTime(8, 30));
    }

    #[On('service::new-barber')]
    public function new_barber($values)
    {
        $this->barber = User::findOrFail($values['barber_id'])->toArray();
        $this->modal_service = true;
        
        $this->barbers[] = [
            'barber' => $this->barber,
            'service' => $values['service_id'],
        ];
    }

    public function open_barber($service_id)
    {
        $barbers = [];

        foreach($this->hours as $hour)
        {
            if($this->hour == $hour['id'])
            {
                $barbers = $hour['barbers'];
            }
        }

        list($horas, $minutos) = explode(':', $this->hour);

        $this->dispatch('barber::select', [
            'barber' => $this->barber,
            'barbers' => $barbers,
            'date' => $this->date->copy()->setTime($horas, $minutos), 
            'service_id' => $service_id,
        ])->to(Barber::class);
        
        $this->modal_service = false;
    }

    #[On('service::barber-cancel')]
    public function barber_cancel()
    {
        $this->modal_service = true;
    }

    public function open_services()
    {
        $ids = [];
        foreach($this->services as $service)
        {
            $ids[] = $service->id;
        }
        $this->dispatch('add::open', $ids)->to(Add::class);
    }

    #[On('service::new-service')]
    public function new_service($id)
    {
        $this->services[] = ModelsService::findOrFail($id);
    }

    public function add_hour($service)
    {
        list($horas, $minutos) = explode(':', $service->duration);

        if ($this->actual_hour && preg_match('/^\d{2}:\d{2}$/', $this->actual_hour)) {
            $novaHora = Carbon::createFromFormat('H:i', $this->actual_hour)
                ->addHours((int) $horas)
                ->addMinutes((int) $minutos)
                ->format('H:i');
        } else {
            $novaHora = '--:--';
        }

        $this->actual_hour = $novaHora;
        return $this->actual_hour;
    }

    public function get_barber($id)
    {
        foreach($this->barbers as $barber)
        {
            if($barber['service'] == $id){
                return $barber['barber']['name'] ?? 'Sem Preferência';
            }
        }

        return 'Sem Preferência';
    }

    public function get_total_price()
    {
        $price = 0;

        foreach ($this->services as $service) {
            $price += $service->price;
        }

        return number_format($price, 2, ',', '.'); // Ex: 1500.5 → 1.500,50
    }

    public function duration()
    {
        // Inicializa os valores totais
        $totalMinutes = 0;

        foreach ($this->services as $service) {
            // Garante que o campo duration está no formato esperado
            if (isset($service->duration)) {
                [$hours, $minutes] = explode(':', $service->duration);
                $totalMinutes += ((int)$hours * 60) + (int)$minutes;
            }
        }

        // Converte total de minutos para horas e minutos
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        // Monta a string final
        $result = ($hours > 0 ? "{$hours}h " : '') . ($minutes > 0 ? "{$minutes}min" : '');

        return trim($result) ?: '0min';
    }


    public function save()
    {
        $hours = $this->calcularHorariosTimestamps($this->date, $this->hour, $this->services);
        $appointments = [];
        for($i = 0; $i < count($this->services); $i++)
        {
            $appointments[] = Appointment::create([
                'user_id' => auth()->user()->id,
                'barber_id' => $this->get_barber_id($this->services[$i]->id) ?? User::query()->whereNotIn('id', $this->haConflitoDeHorario($hours[$i]['start'], $hours[$i]['end'])->pluck('id'))->where('access_level', 1)->get()->first()->id,
                'service_id' => $this->services[$i]->id,
                'start' => $hours[$i]['start'],
                'end' => $hours[$i]['end'],
            ]);
        }

        $this->send_whatsapp($appointments);

        Mail::to(auth()->user()->email)->send(new MailAppointment($appointments));

        $this->success(__('toast_success_title'), __('toast_success_description'));
        $this->modal_service = false;
    }

    public function get_barber_id($id)
    {
        foreach($this->barbers as $barber)
        {
            if($barber['service'] == $id){
                return $barber['barber']['id'];
            }
        }

        return null;
    }

    public function calcularHorariosTimestamps(Carbon $dataSelecionada, string $horaInicial, array $services)
    {
        $horarios = [];

        // Define o início com base na data selecionada e na hora
        $inicio = $dataSelecionada->copy()->setTimeFromTimeString($horaInicial);

        foreach ($services as $service) {
            $start = $inicio->copy();

            // Divide a duração (ex: "01:30")
            [$h, $m] = explode(':', $service->duration);
            $end = $start->copy()->addHours((int) $h)->addMinutes((int) $m);

            $horarios[] = [
                'service_id' => $service->id ?? null,
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
            ];

            // Próximo serviço começa após o término do atual
            $inicio = $end->copy();
        }

        return $horarios;
    }

    protected function send_whatsapp($appointments)
    {
        $message = "*Olá, {$appointments[0]->user->name}!* 👋\n\n";
        $message .= "✨ *Seu agendamento foi confirmado!* Confira os detalhes abaixo:\n\n";

        foreach ($appointments as $index => $appointment) {
            $numero = $index + 1;
            $date = Carbon::parse($appointment->start)->translatedFormat('d \d\e F \d\e Y');
            $start = Carbon::parse($appointment->start)->format('H:i');
            $end = Carbon::parse($appointment->end)->format('H:i');
            $price = number_format($appointment->service->price, 2, ',', '.');

            $message .= "🔹 *Serviço {$numero}:* {$appointment->service->name}\n";
            $message .= "📅 *Data:* {$date}\n";
            $message .= "⏰ *Horário:* {$start} às {$end}\n";
            $message .= "💰 *Preço:* R$ {$price}\n";
            $message .= "💇‍♂️ *Profissional:* {$appointment->barber->name}\n";
            $message .= "─────────────────────────\n";
        }

        $total = $this->get_total_price();

        $message .= "\n🧾 *Total:* _R$ {$total}_\n";
        $message .= "\n🙏 Agradecemos pela preferência! Até breve. ✂️";

        $twilio = new TwilioService;

        $to = env('TWILIO_WHATSAPP_TO');

        $twilio->sendWhatsAppMessage($to, $message);
    }
}
