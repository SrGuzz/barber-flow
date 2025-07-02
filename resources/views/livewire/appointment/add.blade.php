<div>
    @if ($modal_add)
        <x-modal wire:model="modal_add" title="Selecione o serviço" persistent>
            <div class="mb-4">
                <x-input 
                    icon="o-magnifying-glass" 
                    placeholder="Pesquisar serviço"  
                    class="btn-sm focus:outline-none"
                    wire:model.live.debounce="search"
                />
            </div>
            @foreach ($categories as $category)
                <div class="">
                    <p class="text-lg mb-5 font-bold"> {{ $category[0]['category_name'] }} </p>
                    @foreach ($category as $service)
                        <div class="flex justify-between mb-4">
                            <p>{{ $service['name'] }}</p>
                            <div class="flex">
                                <div>
                                    <p class="font-semibold"> R$ {{ $service['price'] }} </p>
                                    <p class="text-end text-xs text-gray-400"> {{ $this->duration($service['duration']) }}</p>
                                </div>
                                <x-button 
                                    label="Reservar" 
                                    class="btn-primary ml-4" 
                                    wire:click="selected_service({{ $service['id'] }})"
                                    spinner
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr class="text-gray-400/50 my-10">
            @endforeach
            
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.modal_add = false" />
            </x-slot:actions>
        </x-modal>
    @endif
</div>
