<?php

namespace App\Livewire\Login;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Logout extends Component
{

    public function mount(){
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        redirect('/');
    }

    public function render()
    {
        return view('livewire.login.logout');
    }
}
