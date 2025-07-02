<div>
    @if ($modal_service)
        <x-modal wire:model="modal_service" persistent without-trap-focus>
            <p class="my-3 text-2xl font-bold text-center" > {{   mb_convert_case($date->monthName, MB_CASE_TITLE, "UTF-8") . ' ' . $date->year }} </p>
            <div class="border-3 border-gray-500/50 rounded-lg mt-6 w-20     py-5 text-center font-semibold m-[0_auto]">
                <p class=" text-md"> {{ Str::ucfirst($date->translatedFormat('D')) }} </p>
                <p class="my-1" > {{ $date->day }} </p>
                <p class="p-1 w-1/3 bg-green-600 rounded-xl m-[0_auto]"></p>
            </div>

            <x-datepicker 
                label="Data" 
                wire:model.live="date" 
                icon="o-calendar" 
                :config="$date_config"
            />            

            <div id="horarios" class="flex items-center space-x-2 overflow-x-auto px-2 py-2 rounded-md bg-base-300 w-full mt-5">
                <x-group  label="Horários" wire:model.live="hour" :options="$hours"/>
            </div>

            <div class="mt-5">
                @php
                    $this->actual_hour = $this->hour;   
                @endphp

                @foreach ($services as $service)
                    <x-card shadow class="bg-base-300 my-4">
                        <div class="flex">
                            <p class="font-semibold">{{ $service->name }}</p>
                            <div class="text-end w-full">
                                <p class="font-semibold">R$ {{ $service->price }} </p>
                                <p class="text-gray-500 text-sm"> {{ $this->actual_hour . ' - ' . $this->add_hour($service) }} </p>
                            </div>
                        </div>

                        <hr class="text-gray-400/50 my-5">

                        <div class="flex">
                            <p class="text-gray-500">Funcionario:  <p class="text-base-content ps-2 w-full"> {{ $this->get_barber($service->id) }} </p></p>
                            <div class="w-full flex justify-end">
                                <x-button label="Alterar" wire:click="open_barber({{ $service->id }})" class="btn-sm bg-base-100" spinner/>
                            </div>
                        </div>
                    </x-card>
                @endforeach

                <x-button 
                    label="Adicionar serviço" 
                    icon="o-plus" 
                    wire:click="open_services()"
                    class="btn-sm btn-link text-primary mt-3"
                />  

                <hr class="text-gray-400/50 my-5">

                <div class="flex justify-end items-center gap-2">
                    <p class="text-xs">Total:</p>
                    <div>
                        <p class="font-semibold text-2xl">R$ {{ $this->get_total_price() }} </p>
                        <p class="text-gray-500 text-end text-sm"> {{ $this->duration() }} </p>
                    </div>
                </div>

            </div>

            <x-slot:actions>
                <x-button class="btn-sm" label="Cancel" @click="$wire.modal_service = false" spinner/>
                <x-button class="btn-sm btn-success" label="Reservar" wire:click="save" spinner/>
            </x-slot:actions>
        </x-modal>
    @endif

    @livewire('appointment.barber')
    @livewire('appointment.add')

</div>
