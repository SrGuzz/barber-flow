<div>
    <x-header title="Informações do Usuário" />

    <x-tabs wire:model="selected_tab">    
        <x-tab name="user" label="Dados" icon="o-user">        
            @livewire('profile.user-profile')    
        </x-tab>    
        <x-tab name="tricks-tab" label="Agendamentos" icon="o-calendar-days">        
            @livewire('profile.appointments')    
        </x-tab>    
    </x-tabs>
</div>
