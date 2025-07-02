<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Index extends Component
{
    public $selected_tab = 'user';

    public function render()
    {
        return view('livewire.profile.index');
    }
}
