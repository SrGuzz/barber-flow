<?php

namespace App\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Exception;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')]
class ForgotPassword extends Component
{

    public ?String $mail = null;


    public function rules() : array
    {
        return[
            'mail' => 'required|email|exists:users,email' ,
        ];
    }

    public function messages() : array
    {
        return[
            'mail.required' => 'O campo de e-mail é obrigatório.', 
            'mail.email' => 'Digite um endereço de e-mail válido.',
            'mail.exists' => 'Esse e-mail não está cadastrado. Tente novamente.'
        ];
    }

    public function linkReset()
    {
        $this->validate();

        $status = Password::sendResetLink(['email'=> $this->mail]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Link de redefinição enviado para seu email!');
        } else {
            session()->flash('error', 'Falha ao enviar link. Tente novamente.');
        }
        
    }

    public function render()
    {
        return view('livewire.login.forgot-password');
    }
}
