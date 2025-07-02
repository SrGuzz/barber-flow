<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Mary\Traits\Toast;

class Appointments extends Component
{
    use Toast;

    public $appointments = [];
    public $appointment;
    public $modal_cancel = false;

    public function render()
    {
        return view('livewire.profile.appointments');
    }

    public function mount()
    {
        $this->appointments = Appointment::with('service', 'barber')
            ->where('user_id', Auth::id())
            ->orderBy('start', 'desc')
            ->get();
    }

    public function open_cancel($id)
    {
        $this->appointment = Appointment::findOrFail($id);
        $this->modal_cancel = true;
    }

    public function cancel()
    {
        $this->appointment->delete();
        $this->modal_cancel = false;
        $this->success(__('toast_success_title'), __('toast_success_description'));
    }
}
