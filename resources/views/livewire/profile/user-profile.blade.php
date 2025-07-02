<div>    
    <div class="w-full max-w-xl mx-auto p-2">
        @php
            $crop = include app_path('View/Components/AvatarCropper.php');
        @endphp

        <div class="flex justify-center mb-2">  
            <x-file wire:model="avatar" accept="image/png, image/jpeg" change-text="{{ ($user->google_id) ? 'Avatar' : 'Alterer' }}" crop-after-change :crop-config="$crop" :disabled="$user->google_id">    
                <img src="{{ ($user->google_id) ? $user->avatar : asset($user->avatar) }}" class="h-36 rounded-full" />
            </x-file>
        </div>

        <div class="p-4">
            <x-form wire:submit="save">
                <x-input
                    label="Nome de usuário"
                    type="name"
                    wire:model="username"
                    :readonly="$user->google_id"
                />
                    
                <x-input
                    label="E-mail de usuário"
                    type="email"
                    wire:model="mail"
                    :readonly="$user->google_id"
                />

                <x-input
                    label="Telefone"
                    wire:model="phone"
                    x-data
                    x-mask="(99) 999999999"
                />
                
                @if(!$user->google_id)
                    <p class="text-gray-400 text-center text-sm pt-9">──────────────   Alterar senha   ──────────────</p>

                    <x-password 
                        label="Senha Atual" 
                        wire:model="actual_password" 
                        clearable 
                    />
                    <x-password 
                        label="Nova Senha" 
                        wire:model="password" 
                        clearable 
                    />
                    <x-password 
                        label="Repita a senha" 
                        wire:model="confirm_password" 
                        clearable 
                    />
                @endif

                <x-slot:actions>
                    <x-button 
                        label="Salvar" 
                        type="submit" 
                        spinner="save" 
                        class="btn-sm btn-success"
                        spinner="save" 
                    />
                </x-slot:actions>
            </x-form>
        </div>
    </div>
</div>
