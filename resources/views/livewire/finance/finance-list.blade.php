<div class="space-y-6">
    <x-header title="Financeiro" subtitle="Deashboard financeiro com detalhes de ganhos do mês selecionado" separator />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title flex items-center gap-2">
                    <x-icon 
                        name="{{ ($this->calcula_porcentagem() > 0) ? 'o-arrow-trending-up' : (($this->calcula_porcentagem() < 0) ? 'o-arrow-trending-down' : 'o-arrow-long-right') }}" 
                        class="w-6 {{ ($this->calcula_porcentagem() > 0) ? 'text-success' : (($this->calcula_porcentagem() < 0) ? 'text-error' : 'text-warning') }}"
                    />
                    Faturamento
                </h3>

                <p class="text-2xl font-semibold {{ ($this->calcula_porcentagem() > 0) ? 'text-success' : (($this->calcula_porcentagem() < 0) ? 'text-error' : 'text-warning') }}">
                    R$ {{ number_format($faturamento, 2, ',', '.') }}
                </p>

                @if ($this->calcula_porcentagem() > 0)
                    <p class="text-sm text-gray-600">{{ number_format($this->calcula_porcentagem(), 1, ',', '.') }}% maior em relação ao mês anterior</p>
                @elseif ($this->calcula_porcentagem() < 0)
                    <p class="text-sm text-gray-600">{{ number_format($this->calcula_porcentagem(), 1, ',', '.') }}% menor em relação ao mês anterior</p>
                @else
                    <p class="text-sm text-gray-600">Permaneceu igual em relação ao mês anterior</p>
                @endif
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title">Agendamentos</h3>
                <p class="text-2xl font-semibold">{{ $num_agendamentos }}</p>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title">Mês Atual</h3>
                <p class="text-2xl font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="flex items-end gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium">Mês</label>
            <select wire:model.live="mesSelecionado" class="select select-sm select-bordered w-full">
                @foreach(range(1, 12) as $mes)
                    <option value="{{ $mes }}">{{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Ano</label>
            <select wire:model.live="anoSelecionado" class="select select-sm select-bordered w-full">
                @foreach(range(now()->year, now()->year - 10) as $ano)
                    <option value="{{ $ano }}">{{ $ano }}</option>
                @endforeach
            </select>
        </div>

        <div class="ml-auto">
            <a href="{{ route('relatory') }}" target="_blank" rel="noopener noreferrer">
            </a>
            <x-button 
                label="Relatório"
                class="btn-sm btn-neutral mt-4" 
                icon="o-arrow-top-right-on-square"
                wire:click="export_relatory()"
                spinner
            />
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h3 class="card-title">Faturamento do mês</h3>
            <div class="h-44">
                <x-chart wire:model="myChart" />
            </div>
        </div>
    </div>
    

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h3 class="card-title">Últimos Atendimentos</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Valor</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ultimos as $appointment)
                            <tr>
                                <td>{{ $appointment->user->name }}</td>
                                <td>{{ $appointment->service->name }}</td>
                                <td>{{ number_format($appointment->service->price, 2, ',', '.') }}</td>
                                <td>{{ Carbon\Carbon::parse($appointment->start)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>