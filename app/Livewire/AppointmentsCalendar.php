<?php

namespace App\Livewire;

use App\Livewire\Appointment\View;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;

class AppointmentsCalendar extends LivewireCalendar
{
    public function events() : Collection
    {
        return Appointment::query()
            ->whereBetween('start', [$this->gridStartsAt, $this->gridEndsAt])
            ->with(['service', 'user', 'barber'])
            ->get()
            ->map(function (Appointment $appointment) {
                $hora = Carbon::parse($appointment->start)->format('H:i');
                
                $user = explode(' ',$appointment->user->name);
                $user = $user[0] . ' ' . $user[1];
                
                $barber = explode(' ',$appointment->barber->name);
                $barber = $barber[0] . ' ' . $barber[1];

                return [
                    'id'          => $appointment->id,
                    'title'       => $appointment->service->name,
                    'description' => 
                        '<strong>Cliente:</strong> ' . $user . 
                        '<br><strong>Hora:</strong> ' . $hora . 
                        '<br><strong>Funcionario:</strong> ' . $barber,
                    'date'        => $appointment->start,
                ];
            });
    }

    public function onEventClick($eventId)
    {
        $this->dispatch('calendar::open-appointment', $eventId)->to(View::class);
    }   
}
