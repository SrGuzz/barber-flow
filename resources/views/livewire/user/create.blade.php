<div>
    @if ($modalCreate)
        <x-modal wire:model="modalCreate" title="Criar Usuario" >
            <x-form wire:submit="save" novalidate>

                <x-input label="Nome" 
                    wire:model="name" 
                    required
                />

                <x-input label="Email" 
                    wire:model="email" 
                />

                <x-input
                    label="Telefone"
                    wire:model="phone"
                    x-data
                    x-mask="(99) 999999999"
                />


                <x-radio 
                    label="Nivel de acesso" 
                    :options="$level" 
                    wire:model="access_level"
                    inline  
                />

                <x-password 
                    label="Senha" 
                    hint="Deve conter maisculas, minusculas e ao menos um caracter especial" 
                    wire:model="password" 
                    clearable 
                />

                <x-password 
                    label="Confirmar senha" 
                    wire:model="confirm_password" 
                    clearable 
                />


                <x-slot:actions>
                    <x-button 
                        label="Cancelar" 
                        @click="$wire.modalCreate = false" 
                        class="btn-sm btn-error"
                    />
                    <x-button 
                        label="Salvar" 
                        type="submit" 
                        spinner="save" 
                        class="btn-sm btn-success"
                        spinner="save" 
                    />
                </x-slot:actions>
            </x-form>
        </x-modal>
    @endif
</div>
