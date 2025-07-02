

<div>

<x-header title="Serviços" size="text-3xl" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input 
                icon="o-magnifying-glass" 
                placeholder="Pesquisar por"  
                class="btn-sm focus:outline-none"
                wire:model.live.debounce="search"
            />
        </x-slot:middle>
        <x-slot:actions>
            <x-button 
                label="Adicionar" 
                class="btn-primary text-white/80 btn-sm" 
                icon="o-plus" 
                wire:click="$dispatchTo('services.create', 'service::open-create')"

            />
        </x-slot:actions>
    </x-header>
    @livewire('services.create')


    <x-card class="">
        <x-table :headers="$headers" :rows="$services" :sort-by="$sortBy" with-pagination show-empty-text empty-text="Nenhum registro encontrado." striped  >
        @scope('actions', $service)
            <div class="flex justify-between">
                    <x-button 
                        icon="o-pencil-square" 
                        spinner 
                        class="btn-ghost btn-sm text-yellow-700" 
                        wire:click="$dispatchTo('services.update', 'service::open-update', { id: {{ $service->id }} })"  
                    />
                    <x-button 
                        icon="o-trash" 
                        spinner 
                        class="btn-ghost btn-sm text-red-500" 
                        wire:click="$dispatchTo('services.destroy', 'service::open-destroy', { id: {{ $service->id }} })"
                    />
                </div>
                @endscope 


                {{-- Formatação de preço --}}
        @scope('cell_price', $service)
            R$ {{ number_format($service->price, 2, ',', '.') }}
        @endscope

        {{-- Formatação de duração --}}
        @scope('cell_duration', $service)
            {{ duration($service->duration) }} 
        @endscope
        </x-table>
    </x-card>
    @livewire('services.update')
    @livewire('services.destroy') 


    @php
        function duration($duration)
        {
            $duration;

            $carbon = Carbon\Carbon::createFromFormat('H:i', $duration);

            $hours = (int) $carbon->format('H');
            $miinutes = (int) $carbon->format('i');

            $result = ($hours > 0 ? "{$hours}h " : '') . ($miinutes > 0 ? "{$miinutes}min" : '');
            
            return $result;
        }
    @endphp
</div>
