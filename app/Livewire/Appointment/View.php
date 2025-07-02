<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use Livewire\Attributes\On;
use Livewire\Component;

class View extends Component
{
    public $appointment;
    public $modal = false;

    public function render()
    {
        return view('livewire.appointment.view');
    }

    #[On('calendar::open-appointment')]
    public function show_modal($id)
    {
        $this->appointment = Appointment::findOrFail($id);
        $this->modal = true;
    }
}
