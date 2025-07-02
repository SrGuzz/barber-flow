<div>
    <x-header title="Agendamestos do mês de {{ Str::of(now()->locale('pt_BR')->monthName)->title() }}" />
    <livewire:appointments-calendar
        week-starts-at=1
        :drag-and-drop-enabled="false"
    />

    @if ($modal)
        <x-modal wire:model="modal" title="Agendamento" class="backdrop-blur" without-trap-focus>
            <div class="grid gap-y-5">

                <x-input 
                    inline 
                    label="Cliente" 
                    value="{{ $appointment->user->name }}" 
                    readonly 
                />
                <x-input 
                    inline 
                    label="Funcionario" 
                    value="{{ $appointment->barber->name }}" 
                    readonly 
                />
                <x-input 
                    inline 
                    label="Serviço" 
                    value="{{ $appointment->service->name }}" 
                    readonly 
                />
                <x-input 
                    inline 
                    label="Horario" 
                    value="{{ Carbon\Carbon::parse($appointment->start)->format('H:i') }}" 
                    readonly 
                />
                <x-input 
                    inline 
                    label="Status" 
                    class="{{ (!$appointment->confirmed_at) ? 'text-error' : ((!\Carbon\Carbon::parse($appointment->end)->isPast()) ? 'text-warning' : 'text-success') }}"
                    value="{{ (!$appointment->confirmed_at) ? 'Em aberto' : ((!\Carbon\Carbon::parse($appointment->end)->isPast()) ? 'Confirmado' : 'Finalizado') }}" 
                    readonly 
                />
            </div>
        </x-modal>
    @endif
</div>
