<?php

namespace App\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Exception;
use Livewire\Attributes\Layout;
use phpDocumentor\Reflection\Types\Boolean;

#[Layout('components.layouts.guest')]
class Form extends Component
{
    protected $layout = 'components.layouts.guest'; 

    public ?string $mail = null;


    public ?string $password = null;


    public ?bool $remember = false;


    public ?string $recaptcha = null;


    public function rules() : array
    {
        return[
            'mail' => 'required|email',
            'password' => [
                'required',
                'min:8',
            ],
        ];
    }


    public function messages(): array
    {
        return[
            'mail.required' => 'O campo de e-mail é obrigatório.',
            'mail.email' => 'Digite um endereço de e-mail válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'Senha deve conter ao menos 8 caracteres.',
        ];
    }

    public function recaptchaValidation(){

        if(!$this->recaptcha){
            $this->addError('recaptcha' , 'Erro: reCAPTCHA não preenchido.');
            return;
        }

        // request api
        $response = Http::asForm()
        ->withOptions([
            'verify' => app()->environment('production')    //true on production
            ])
        ->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $this->recaptcha
        ]);

        $responseData = $response->json();

        // verification if goes wrong
        if(!$responseData['success']){
            $this->addError('recaptcha' , 'Falha na verificação reCAPTCHA. Tente novamente.');
        }
    }

    public function login(){
        $this->validate();

        $this->recaptchaValidation();

        if($this->getErrorBag()->has('recaptcha')){
            return;
        }

        try{


            $credentials = Auth::attempt([
                'email' => $this->mail,
                'password'=> $this->password,
                'status' => true
            ] , $this->remember
        );


            if(!$credentials){
                $this->addError('mail', 'Email ou senha inválidos. Tente novamente.');


                return;
            }
            
            return  $this->redirect('/profile');


        } catch (Exception $e){
            $this->addError('mail', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.login.form');
    }
}
