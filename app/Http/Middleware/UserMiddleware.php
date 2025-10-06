<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->is_admin())
        {
            return $next($request);
        }
        
        // Sugestão: usar abort_if/abort_unless com trans() para mensagens localizáveis.
        // Benefício: facilita tradução e testes, além de manter consistência nas respostas.
        // Sugestão: considerar retornar uma resposta JSON padronizada para APIs (aceitar header X).
        // Benefício: melhora UX para clientes API e facilita debugging.
        abort(403, trans('auth.denied', [], 'pt-BR'));
    }
}
