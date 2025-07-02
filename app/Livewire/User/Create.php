<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public $name;

    public $email;

    public $password;

    public $phone;

    public $access_level = 0;

    public $confirm_password;

    public $modalCreate = false;

    public $level = [
        ['name' => 'Admin' , 'id' => 1],
        ['name' => 'Usuario' , 'id' => 0],
    ];

    public function render()
    {
        return view('livewire.user.create');
    }

    #[On('user::open-create')]
    public function showModalCreate()
    {
        $this->modalCreate = true;
    }

    public function save()
    {
        $user = $this->validate();


        try{
            $user = User::create($user);

            $this->reset();

            $this->modalCreate = false;

            $this->dispatch('user::refresh');

            $this->success(
                'Sucesso!',
                'Cadastro realizado com sucesso.',
                position: 'toast-start toast-bottom',
            );
        }catch(\Exception $e) {
            $this->error(
                'Opss!',
                'Erro ao salvar, por gentileza entre em contato com o time de TI',
                position: 'toast-start toast-bottom',
                icon: 'o-face-frown'
            );
        }
    }

    public function rules()
    {
        return [
            'name'  => 'required|min:3',
            'email' => 'required|email:rfc,dns|unique:users',
            'phone' => ['required', 'regex:/^\([1-9]{2}\)\s9?[0-9]{4}-[0-9]{4}$/'],
            'password'    => [
                'required',
                'min:8',
                'string',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
            'confirm_password' => 'required|same:password',
            'access_level'     => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'A Senha deve conter pelo menos: uma letra maiúscula, uma letra minúscula, um número e um caractere especial (ex: @$!%*?&).',
        ];
    }
}
