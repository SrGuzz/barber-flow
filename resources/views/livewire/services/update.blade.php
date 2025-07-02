<div>
    @if ($modalUpdate)
        @php
            $crop = include app_path('View/Components/Cropper.php');
        @endphp
        <x-modal wire:model="modalUpdate" title="Atualizar Serviço">
            <x-form wire:submit="save">

                <x-input 
                    label="Nome Serviço" 
                    wire:model="name" 
                />

                <div wire:key="service-{{ $this->service->id }}">
                    <x-input 
                        label="Preço" 
                        wire:model="price"
                        prefix="R$" 
                        locale="pt-BR" 
                        money 
                    />
                </div>

                <x-select label="Categoria" wire:model="category_id" :options="$categories" >
                    @if (count($categories) > 0)
                        <x-slot:prepend>
                            <x-button 
                                icon="o-trash"
                                class="join-item text-error" 
                                wire:click="$dispatchTo('category.destroy', 'category::open-destroy', { id: {{ $category_id }} })"
                            />
                        </x-slot:prepend>
                    @endif
                    <x-slot:append>
                        <x-button 
                            icon="o-plus" 
                            class="join-item btn-primary" 
                            wire:click="$dispatchTo('category.create', 'category::open-create')"
                        />
                    </x-slot:append>
                </x-select>

                <x-input 
                    label="Descrição" 
                    wire:model="description" 
                />

                <!-- Campo de duração com select list -->
                <x-datetime 
                    label="Duração" 
                    wire:model="duration" 
                    icon="o-calendar" 
                    type="time"  
                />

                <div wire:key="imagem-{{ $this->service->id }}">
                    <x-file wire:model.live="avatar" accept="image/*" crop-after-change :crop-config="$crop" class="crop-create" label="Foto">
                        <img src="{{ asset($this->avatar) }}" class="h-32 rounded-lg" />
                    </x-file>
                </div>
                
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
                    />
                </x-slot:actions>
            </x-form>
        </x-modal>

        @livewire('category.create')
        @livewire('category.destroy')
    @endif
</div>