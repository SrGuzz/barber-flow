<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LandingPage extends Component
{
    public function mount() 
    {
        if(Auth::check()){
            return redirect('/profile');
        }
    }

    public function render()
    {
        return view('livewire.landing-page.landing-page')->layout('components.layouts.layout-landing');;
    }
}
