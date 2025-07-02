<?php

namespace App\Livewire\Appointment;

use App\Livewire\Appointment\Service as AppointmentService;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Add extends Component
{
    public $categories;
    
    public $modal_add = false;
    public $service_id;

    public function render()
    {
        return view('livewire.appointment.add');
    }

    #[On('add::open')]
    public function open_add($ids)
    {
        $this->categories = Service::query()
            ->with('category')
            ->withAggregate('category', 'name')
            ->whereNotIn('id', $ids)
            ->get()
            ->groupBy('category_name')
            ->toArray();

        $this->modal_add = true;
    }

    public function duration($duration)
    {
        $duration;

        $carbon = Carbon::createFromFormat('H:i', $duration);

        $hours = (int) $carbon->format('H');
        $miinutes = (int) $carbon->format('i');

        $result = ($hours > 0 ? "{$hours}h " : '') . ($miinutes > 0 ? "{$miinutes}min" : '');
        
        return $result;
    }

    public function selected_service($id)
    {
        $this->dispatch('service::new-service', $id)->to(AppointmentService::class);
        $this->modal_add = false;
    }
}
