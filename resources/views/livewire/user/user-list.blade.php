<div>
    <x-header title="Usuarios" size="text-3xl" separator progress-indicator>
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
                wire:click="$dispatchTo('user.create', 'user::open-create')"
            />
        </x-slot:actions>
    </x-header>
    @livewire('user.create')

    <x-card class="">
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination show-empty-text empty-text="Nenhum registro encontrado." striped  >
            @scope('cell_access_level', $user)
                {{ $user->access_level ? 'Admin' : 'Funcionario' }}
            @endscope
            
            @scope('cell_status', $user)
                {{ $user->status ? 'Ativo' : 'Inativo' }}
            @endscope
            
            @scope('actions', $user)
                <div class="flex justify-between">
                    <x-button 
                        icon="o-pencil-square" 
                        spinner 
                        class="btn-ghost btn-sm text-yellow-700" 
                        wire:click="$dispatchTo('user.update', 'user::open-update', { id: {{ $user->id }} })" 
                    />
                    <x-button 
                        icon="o-trash" 
                        spinner 
                        class="btn-ghost btn-sm text-red-500" 
                        wire:click="$dispatchTo('user.destroy', 'user::open-destroy', { id: {{ $user->id }} })" 
                    />
                </div> 
            @endscope
        </x-table>
    </x-card>
    @livewire('user.update')
    @livewire('user.destroy') 
</div>
