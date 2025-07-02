<div class="relative w-full max-w-sm p-6 bg-base-100 rounded-lg shadow-lg">

    <form wire:submit.prevent="resetPassword">
        <h1 class="text-2xl font-semibold mb-6 text-center">Redefinir Senha</h1>

        <x-input
            label="E-mail"
            type="email"
            wire:model="mail"
            class="mb-4"
        />

        <x-password
            label="Nova Senha"
            wire:model="password"
            password-icon="o-lock-closed"
            password-visible-icon="o-lock-open"
            input-class="pl-10 pr-10"
            class="mb-4"
        />

        <x-password
            label="Confirmar Senha"
            wire:model="password_confirmation"
            password-icon="o-lock-closed"
            password-visible-icon="o-lock-open"
            input-class="pl-10 pr-10"
            class="mb-4"
        />

        <x-button type="submit" class="btn-primary w-full mb-6">
            Redefinir Senha
        </x-button>

        <p class="text-center">
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
