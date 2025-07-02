<div class="space-y-4">
    <h2 class="text-xl font-bold">Histórico de Agendamentos</h2>

    @if($appointments->isEmpty())
        <p class="text-gray-500">Nenhum agendamento encontrado.</p>
    @else
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach($appointments as $appointment)
                <x-card title="{{ $appointment->service->name }}" shadow separator>
                    <p class="text-sm text-gray-600">
                        <strong>Funcionario:</strong> {{ $appointment->barber->name }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Data:</strong> {{ \Carbon\Carbon::parse($appointment->start)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Valor:</strong> R$ {{ $appointment->service->price }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Status:</strong> {{ (!$appointment->confirmed_at) ? 'Em aberto' : ((\Carbon\Carbon::parse($appointment->end) < \Carbon\Carbon::now()) ? 'Confirmado' : 'Finalizado')}}
                    </p>
                    <x-slot:menu>
                        @if (!$appointment->confirmed_at)
                            <x-button 
                                icon="o-x-mark" 
                                class="btn-link text-error btn-sm"
                                spinner 
                                wire:click="open_cancel({{ $appointment->id }})"
                            />
                        @endif
                    </x-slot:menu>
                </x-card>
            @endforeach
        </div>

        @if($modal_cancel)
            <x-modal wire:model="modal_cancel" title="Apagar Categoria" >
                <div class="text-left">Tem certeza que deseja cancelar o agendamento de {{$appointment->service->name}}?</div>
                <x-slot:actions>
                    <x-button 
                        label="Cancelar" 
                        class=" btn-sm" 
                        @click="$wire.modal_cancel = false" 
                    />
                    <x-button 
                        label="Confirmar" 
                        class="btn-error btn-sm" 
                        wire:click="cancel()"
                        spinner 
                    />
                </x-slot:actions>
            </x-modal>
        @endif
    @endif
</div>