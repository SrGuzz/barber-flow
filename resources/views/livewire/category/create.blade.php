<div>
    @if ($modal_create)
        <x-modal wire:model="modal_create" title="Criar Categoria" >
            <x-form wire:submit="save">

                <x-input label="Nome" 
                    wire:model="name" 
                />

                <x-slot:actions>
                    <x-button 
                        label="Cancelar" 
                        @click="$wire.modal_create = false" 
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
