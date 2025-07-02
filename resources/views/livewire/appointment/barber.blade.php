<div>
    @if ($modal_barber)
        <x-modal wire:model="modal_barber" title="Funcionarios disponiveis para o horário" persistent>
            
            @foreach($barbers as $barber)
                <x-list-item :item="$barber" no-hover>
                    <x-slot:sub-value>
                        <p class="{{ $barber['id'] == $this->barber['id'] ? 'text-info' : ($barber['disponible'] ? 'text-success' : 'text-error') }}">
                            {{ $barber['id'] == $this->barber['id'] ? 'Atual' : ($barber['disponible'] ? 'disponivel' : 'indisponivel') }}
                        </p>

                    </x-slot:sub-value>

                    <x-slot:actions>
                        <x-button icon="o-check" class="btn-sm btn-link text-success {{ (!$barber['disponible'] || $barber['id'] == $this->barber['id']) ? 'hidden' : '' }}" wire:click="selected_barber({{ $barber['id'] }})" spinner />
                    </x-slot:actions>
                </x-list-item>
            @endforeach

            <x-slot:actions>
                <x-button label="Cancel" wire:click="close_modal" spinner/>
            </x-slot:actions>
        </x-modal>
    @endif
</div>
