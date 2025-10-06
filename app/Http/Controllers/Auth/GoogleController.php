<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class GoogleController extends Controller
{
    public function redirect(Request $request, string $provider)
    {

        $this->validateProvider($request);

        return Socialite::driver($provider)->redirect();
        
    }

    public function callback(Request $request, String $provider)
    {
        $this->validateProvider($request);
        
        $response = Socialite::driver('google')->user();

        // Sugestão: mover a lógica de criação/atualização de usuário para um UserService.
        // Justificativa: separa responsabilidade do controller e facilita teste unitário da lógica de negócio.

        //verifica se ja existe
        $user = User::where('email', $response->getEmail())->first();

        //se ja existe
        if($user && !$user->google_id){
            // Sugestão: padronizar mensagens de erro via localization (trans()).
            // Benefício: facilita tradução e manutenção das mensagens de usuário.
            return redirect()->route('login')->withErrors([
                'email' => 'Já existe uma conta com esse e-mail.'
            ]);
        }

        //criando user
        $user = User::firstOrCreate(
            ['email' => $response->getEmail()],
            ['password' => bcrypt(Str::random(16))],
        );

        // Sugestão: use updateOrCreate com campos explícitos para evitar race conditions em alto tráfego.
        // Benefício: operação atômica reduz chances de duplicação ou inconsistência.

        $user->update([
            'google_id' => $response->getId()
        ]);

        if($user->wasRecentlyCreated){
            event(new Registered(($user)));
        }

        // Sugestão: use loginUsingId ou autenticação via guard para clareza e possível configuração de remember.
        // Benefício: torna intenção explícita e fácil de ajustar para múltiplos guards.
        Auth::login($user, remember: true);
        return redirect()->intended('/users');

    }

    protected function validateProvider(Request $request): array
    {
        return Validator::make(
            $request->route()->parameters(),
            ['provider' => 'in:google']
        )->validate();
    }
    
}
