<?php

namespace App\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')]
class ResetPassword extends Component
{
    public $token;

    public $mail = null;

    public $password = null;

    public $password_confirmation = null;

    public function rules() : array
    {
        return[
            'mail' => 'required|email' ,
            'password' => 'required|min:6|confirmed'
        ];

    }

    public function messages() : array{
        return[
            'mail.required' => 'O campo de email é obrigatório',
            'mail.email' => 'Email deve ser válido',
            'password.required' => 'O campo de senha é obrigatório',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
        ];
    }

    public function mount($token)
    {
        $this->token = $token;
    }
    
    public function resetPassword()
    {
        $this->validate();
    
        $status = Password::reset( 
            [
                'email' => $this->mail,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password), 
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );
    
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Senha alterada com sucesso!');
        } else {
            session()->flash('error', 'Falha ao redefinir a senha. Verifique os dados.');
        }
    }

    public function render()
    {
        return view('livewire.login.reset-password');
    }
}
