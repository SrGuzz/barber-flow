<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('components.layouts.app')]
class UserProfile extends Component
{
    use WithFileUploads;
    use Toast;

    public $username;
    public $mail;
    public $phone;
    public $password;
    public $actual_password;
    public $confirm_password;
    public $photo; 
    public $avatar;
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->username = $this->user->name;
        $this->mail = $this->user->email;
        $this->phone = $this->user->phone;
        $this->password = '';
    }

    public function save()
    {
        $user = $this->validate();

        if ($this->avatar instanceof TemporaryUploadedFile) {
            $this->validate(['avatar' => 'image|mimes:jpeg,png,jpg,webp']);

            $filename = $this->avatar->getClientOriginalName();
            $path = $this->avatar->storeAs('fotos',$filename, 'public');
            $user['avatar'] = '/storage/' . $path;
        }

        if(!$this->password && !$this->actual_password)
        {
            auth()->user()->update($user);
            $this->success(
                __('toast_success_title'),
                __('toast_success_description'),
                position: 'toast-start toast-bottom',
            );
            return;
        }

        if($this->actual_password && $this->password && Hash::check($this->actual_password, auth()->user()->password))
        {
            if(Hash::check($this->password, auth()->user()->password))
            {
                $this->reset(['confirm_password']);
                return $this->addError('password', 'A nova senha deve ser diferente da anterior!');
            }

            

            auth()->user()->update($user);
            $this->success(
                __('toast_success_title'),
                __('toast_success_description'),
                position: 'toast-start toast-bottom',
            );
        }
        else
        {
            return $this->addError('actual_password', 'Senha incorreta!');
        }
    }

    public function rules()
    {
        return [
            'username' => 'required|min:3',
            'phone' => ['required', 'regex:/^\([1-9]{2}\)\s9?[0-9]{8}$/'],
            'password' => [
                'nullable', // porque o user pode não querer alterar
                'min:8',
                'string',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
            'confirm_password' => 'nullable|same:password',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Formato de telefone inválido. Ex: (99) 91234-5678',
            'password.regex' => 'A senha deve conter pelo menos: uma letra maiúscula, uma minúscula, um número e um caractere especial (ex: @$!%*?&).',
        ];
    }



    public function render()
    {

        return view('livewire.profile.user-profile');
    }
}
