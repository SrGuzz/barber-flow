<div>
    <x-header title="Serviços disponíveis" separator />
    @if (auth()->user()->favorites->count())
        
        <p class="text-xl mb-5 font-bold"> Favoritos </p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-y-10 lg:gap-y-5">
            @foreach (auth()->user()->favorites as $favorite)
                <x-card title="{{ $favorite->service->name }}" shadow>
                    <div class="text-end">
                        <p class="font-semibold"> R$ {{ $favorite->service->price }} </p>
                        <p class="text-xs text-gray-400"> {{ $this->duration($favorite->service->duration) }}</p>
                        
                    </div>
                    
                    <x-slot:figure>
                        <img src="{{ $favorite->service->avatar }}" />
                    </x-slot:figure>
                    <x-slot:menu>
                        <x-button 
                            class="btn-link text-neutral  p-0 cursor-pointer {{ ($this->is_favorite($favorite->service->id)) ? 'text-red-500' : '' }}" 
                            wire:click="favorite({{ $favorite->service->id }})"
                            icon="{{ ($this->is_favorite($favorite->service->id)) ? 's-heart' : 'o-heart' }}"
                        />
                    </x-slot:menu>
                    <x-slot:actions separator>
                        <x-button 
                            label="Reservar" 
                            class="btn-primary btn-sm" 
                            wire:click="$dispatchTo('appointment.service', 'service::open', {id: {{ $favorite->service->id }} })"
                        />
                    </x-slot:actions>
                </x-card>
            @endforeach    
        </div>
        
        <hr class="text-gray-400/50 my-10">
    @endif

    @foreach ($categories as $category)
        <p class="text-xl mb-5 font-bold"> {{ $category->first()->category_name }} </p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach ($category as $service)
                <x-card title="{{ $service->name }}" shadow>
                    <div class="text-end">
                        <p class="font-semibold"> R$ {{ $service->price }} </p>
                        <p class="text-xs text-gray-400"> {{ $this->duration($service->duration) }}</p>
                        
                    </div>
                    
                    <x-slot:figure>
                        <img src="{{ $service->avatar }}" />
                    </x-slot:figure>
                    <x-slot:menu>
                        <x-button 
                            class="btn-link text-neutral  p-0 cursor-pointer {{ ($this->is_favorite($service->id)) ? 'text-red-500' : '' }}" 
                            wire:click="favorite({{ $service->id }})"
                            icon="{{ ($this->is_favorite($service->id)) ? 's-heart' : 'o-heart' }}"
                        />
                    </x-slot:menu>
                    <x-slot:actions separator>
                        <x-button 
                            label="Reservar" 
                            class="btn-primary btn-sm" 
                            wire:click="$dispatchTo('appointment.service', 'service::open', {id: {{ $service->id }} })"
                        />
                    </x-slot:actions>
                </x-card>
            @endforeach    
        </div>

        <hr class="text-gray-400/50 my-10">
    @endforeach

    @livewire('appointment.service')
</div>
