<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');
        Vite::useBuildDirectory('build/.vite');
        // Sugestão: registrar bindings por interface aqui, ex: UserRepositoryInterface -> EloquentUserRepository.
        // Benefício: desacopla implementação e facilita swapping/mocking em testes.

        // Sugestão: somente forçar URL::forceScheme('https') em produção verificando config('app.env').
        // Benefício: evita forçar https em ambientes de desenvolvimento onde não está configurado.
    }
}
