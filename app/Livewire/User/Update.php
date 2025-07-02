<?php

namespace App\Livewire\User;

use App\Models\AdministrativeUnit;
use App\Models\Position;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public $user;

    public $name;

    public $phone;

    public $email;

    public $status;

    public $access_level;

    public $modalUpdate = false;

    public $level = [
        ['name' => 'Admin' , 'id' => 1],
        ['name' => 'Usuario' , 'id' => 0],
    ];

    public $has_active = [
        ['name' => 'Ativo' , 'id' => 1],
        ['name' => 'Inativo' , 'id' => 0],
    ];

    public function render()
    {
        return view('livewire.user.update');
    }

    #[On('user::open-update')]
    public function showModalUpdate($id)
    {
        $this->user = User::findOrFail($id);

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->status = $this->user->status;
        $this->access_level = $this->user->access_level;

        $this->modalUpdate = true;
    }

    public function save()
    {
        $new_user = $this->validate();

        try {
            $this->user->update($new_user);

            $this->reset();

            $this->modalUpdate = false;

            $this->dispatch('user::refresh');

            $this->success(
                __('toast_success_title'),
                __('toast_success_description'),
                position: 'toast-start toast-bottom',
            );
        } catch (\Exception $e) {
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
            'email' => 'required|email:rfc,dns|unique:users,email,' . $this->user->id,
            'phone' => ['required', 'regex:/^\([1-9]{2}\)\s9?[0-9]{4}-[0-9]{4}$/', 'unique:users,phone,' . $this->user->id],
            'access_level'     => 'required|boolean',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'masp.regex' => 'O masp deve conter pelo menos: uma letra e números (ex: a1234567).',
        ];
    }
}
