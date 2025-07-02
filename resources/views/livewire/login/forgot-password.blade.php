<div class="relative w-full max-w-sm p-6 bg-base-100 rounded-lg shadow-lg">

    <form wire:submit.prevent="linkReset">
        <h1 class="text-2xl font-semibold mb-6 text-center">Recuperação de Senha</h1>

        <x-input
            label="E-mail"
            type="email"
            wire:model="mail"
        />

        <x-button type="submit" class="btn-primary w-full mt-5">
            Enviar link de recuperação
        </x-button>

        <p class="text-center mt-5">
            <a href="/login" class="text-sm hover:underline">
                Voltar para o login
            </a>
        </p>
    </form>

    @if (session()->has('status'))
        <p class="text-success text-center mt-4">
            {{ session('status') }}
        </p>
    @endif

    @if (session()->has('error'))
        <p class="text-error text-center mt-4">
            {{ session('error') }}
        </p>
    @endif
</div>

