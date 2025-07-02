<?php

namespace App\Livewire\SignUp;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;


#[Layout('components.layouts.guest')]
class Cadastro extends Component
{
    protected $layout = 'components.layouts.guest'; 


    public ?string $username = null;


    public ?string $mail = null;


    public ?string $phone = null;


    public ?string $password = null;


    public ?string $credentials =  null;


    public ?string  $password_confirmation = null;


    public function rules() : array
    {
        return[
            'username' => 'required|min:3|unique:users,name',
            'mail' => 'required|email:rfc,dns|unique:users,email',
            'phone' => [
                'required', 'regex:/^\([1-9]{2}\)\s9?[0-9]{4}-[0-9]{4}$/'
            ],
            'password' => [
                'required',
                'min:8',
                'string',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ];
    }


    public function messages() : array
    {
        return[
            'username.required' => 'O campo de nome de usuário é obrigatório.',
            'username.unique' => 'Nome de usuário indisponível.',
            'mail.required' => 'O campo de e-mail é obrigatório.',
            'mail.email' => 'Digite um endereço de e-mail válido.',
            'mail.unique' => 'E-mail já está em uso.',
            'phone.required' => 'Campo telefone é obrigatório.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não correspondem.',
            'password.regex' => 'A senha deve conter pelo menos: uma letra maiúscula, uma letra minúscula, um número e um caractere especial (ex: @$!%*?&).',
        ]; 
    }


    public function save()
    {
        $this->validate();

        try{

            User::create([
                'name' => $this->username,
                'email' => $this->mail,
                'phone' => $this->phone,
                'password' =>Hash::make($this->password),
            ]);

            session()->flash('success', 'Cadastro realizado com sucesso');

            return redirect()->to('/login');
        
        } catch(Exception $e){
            $this->addError('mail', $e->getMessage());
        }

    }

    
    public function render()
    {
        return view('livewire.sign-up.cadastro');
    }
}
