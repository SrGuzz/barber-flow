<div>
    @if ($modalUpdate)
        <x-modal wire:model="modalUpdate" title="Atualizar Usuario" >
            <x-form wire:submit="save">

                <x-input label="Nome" 
                    wire:model="name" 
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
                    label="Status" 
                    :options="$has_active" 
                    wire:model="status"
                    inline 
                />

                <x-radio 
                    label="Nivel de acesso" 
                    :options="$level" 
                    wire:model="access_level"
                    inline 
                />

                <x-slot:actions>
                    <x-button 
                        label="Cancelar" 
                        @click="$wire.modalUpdate = false" 
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
