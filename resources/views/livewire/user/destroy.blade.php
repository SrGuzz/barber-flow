<div>
    @if($modalDestroy)
        <x-modal wire:model="modalDestroy" title="Apagar Usuario" >
            <div class="text-left">Tem certeza que deseja apagar o usuario {{$user->name}}?</div>
            <x-slot:actions>
                <x-button 
                    label="Cancelar" 
                    class=" btn-sm" @click="$wire.modalDestroy = false" 
                />
                <x-button 
                    label="Confirmar" 
                    class="btn-error btn-sm" wire:click="delete" 
                />
            </x-slot:actions>
        </x-modal>
    @endif
</div>
