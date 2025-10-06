<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Middleware\UserMiddleware;
use App\Livewire\User\UserList;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;
use App\Livewire\Login\Form;
use App\Livewire\Login\ForgotPassword;
use App\Livewire\Login\Logout;
use App\Livewire\Login\ResetPassword;
use App\Livewire\Profile\UserProfile;
use App\Livewire\SignUp\Cadastro;
use App\Livewire\LandingPage\LandingPage;
use App\Livewire\Appointment\AppointmentList;
use App\Livewire\Appointment\View;
use App\Livewire\Services\ServicesList;
use App\Livewire\Finance\FinanceList;
use App\Livewire\Profile\Index;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', LandingPage::class)->name('index');

// Rotas para usuários não logados
Route::middleware('guest')->group(function(){
    Route::get('/login', Form::class)->name('login');
    Route::get('/sign-up', Cadastro::class)->name('sign-up');

    // forgot password
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::post('/forgot-password', [ForgotPassword::class, 'sendResetLink'])->name('password.email');

    // reset password
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::post('/reset-password', [ResetPassword::class, 'resetPassword'])->name('password.update');

    Route::get('/auth/google', function () {
        // Sugestão: delegar redirecionamento ao GoogleController::redirect para manter lógica centralizada.
        // Benefício: facilita adicionar logs, válidação de provider e testes sem closures anônimos nas rotas.
        return Socialite::driver('google')->redirect();
    })->name('auth.google');

    Route::get('/auth/google/callback', function () {
        // Sugestão: mover lógica de callback para um Controller dedicado (ex: GoogleController@callback).
        // Benefício: evita closures nas rotas, melhora separação de responsabilidades e facilita testes.
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Sugestão: validar e sanitizar dados do provedor antes de persistir.
        // Benefício: protege contra dados inesperados e melhora segurança do banco.

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],   
            [                                       
                'name'      => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]
        );


        Auth::login($user);

        // Sugestão: usar named route ou config para redirecionamento pós-login ao invés de rota hardcoded.
        // Benefício: facilita mudança de fluxo sem alterar código em múltiplos lugares.
        return redirect()->route('profile'); // ajuste conforme sua rota
    });
});

// Rotas para logados
Route::middleware('auth')->group(function(){
    
    Route::get('/profile', Index::class)->name('profile');
    Route::get('/logout', Logout::class); 
    Route::get('/appointments', AppointmentList::class)->name('appointments');
    
    Route::middleware([UserMiddleware::class])->group(function(){
        Route::get('/users', UserList::class);
        // Sugestão: adicionar ->name('users.index') e seguir convensão RESTful para facilitar geração de URLs.
        // Benefício: consistência e interoperabilidade com helpers e políticas.
        Route::get('/finance', FinanceList::class);
        Route::get('/services', ServicesList::class);
        // Sugestão: usar verbo POST/GET adequado e nomear rota como 'finance.reports.export' para clareza.
        // Benefício: segue padrões REST e documenta intenção da rota.
        Route::get('/relatory', [FinanceList::class, 'export_relatory'])->name('relatory');
        Route::get('/calendar', View::class); 
    });

});

