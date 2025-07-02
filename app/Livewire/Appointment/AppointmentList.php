<?php

namespace App\Livewire\Appointment;

use App\Models\Favorite;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentList extends Component
{
    public function render()
    {
        $categories = Service::query()
            ->with('category')
            ->withAggregate('category', 'name')
            ->get()
            ->groupBy('category_name');
        
        return view('livewire.appointment.appointment-list', ['categories' => $categories]);
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

    public function favorite($service_id)
    {
        if(count($favorite = auth()->user()->favorites()->where('service_id', $service_id)->get()))
        {
            $favorite->first()->delete();
        }
        else
        {
            Favorite::create(['service_id' => $service_id, 'user_id' => auth()->user()->id]);
        }
    }

    public function is_favorite($service_id)
    {
        return Favorite::where('user_id', auth()->user()->id)->where('service_id', $service_id)->get()->count();    
    }
}
