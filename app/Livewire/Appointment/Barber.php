<?php

namespace App\Livewire\Appointment;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Barber extends Component
{
    public $modal_barber = false;
    public $barber;
    public $service_id;

    public $barbers;

    public function render()
    {
        return view('livewire.appointment.barber');
    }

    #[On('barber::select')]
    public function showBarber($values) 
    {
        $this->barber = $values['barber'];
        $this->service_id = $values['service_id'];
        
        $ids = array_column($values['barbers'], 'id');

        foreach($values['barbers'] as &$barber)
        {
            $barber['disponible'] = true;
        }

        array_shift($ids);

        $barbers_ocuped = User::query()->whereNotIn('id', $ids)->where('access_level', 1)->get()->toArray();

        if(count($barbers_ocuped) != count($values['barbers'])){
            foreach($barbers_ocuped as &$barber)
            {
                $barber['disponible'] = false;
                $values['barbers'][] = $barber;
            }
        }

        $this->barbers = $values['barbers'];
        $this->modal_barber = true;
    }

    public function selected_barber($barber_id)
    {
        $this->dispatch('service::new-barber', [ 'barber_id' => $barber_id, 'service_id' => $this->service_id ])->to(Service::class);
        $this->modal_barber = false;
    }

    public function close_modal()
    {
        $this->dispatch('service::barber-cancel')->to(Service::class);
        $this->modal_barber = false;
    }


}
