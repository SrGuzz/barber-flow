<?php

namespace App\Livewire\Finance;

use App\Models\Appointment;
use Livewire\Component;
use Carbon\Carbon;
use PDF;

class FinanceList extends Component
{
    public array $myChart = [];
    public int $mesSelecionado = 0;
    public int $anoSelecionado = 0;
    public $appointments;

    public $faturamento = 0;
    public $num_agendamentos = 0;
    public $ultimos;

    public function mount()
    {
        $this->mesSelecionado = now()->month;
        $this->anoSelecionado = now()->year;
        $this->init();
    }

    public function updatedMesSelecionado($value)
    {
        $this->init();
    }

    public function updatedAnoSelecionado($value)
    {
        $this->init();
    }

    public function init()
    {
        $this->appointments = Appointment::whereMonth('start', $this->mesSelecionado)    
            ->whereYear('start', $this->anoSelecionado)                    // Ano específico
            ->get();

        $this->num_agendamentos = $this->appointments->count();
        
        $this->faturamento = $this->appointments->sum(function ($appointment) {
            return $appointment->service->price ?? 0;
        });

        $this->ultimos = Appointment::latest('created_at')
            ->take(5)
            ->get();
    }
    
    public function updated($property)
    {
        $this->gerarGrafico();
    }

    public function gerarGrafico()
    {
        $numeroDias = Carbon::create($this->anoSelecionado, $this->mesSelecionado)->daysInMonth;

        $this->myChart = [
            'type' => 'line',
            'data' => [
                'datasets' => [[
                    'label' => 'Faturamento diário (R$)',
                    'data' => $this->soma_dias(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ]]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => "function(value) { return 'R$ ' + value.toLocaleString('pt-BR'); }"
                        ]
                    ]
                ]
            ]
        ];
    }

    public function render()
    {
        $this->gerarGrafico();
        return view('livewire.finance.finance-list');
    }

    public function calcula_porcentagem()
    {
        // total mês atual
        $totalAtual = Appointment::with('service')
            ->whereMonth('start', $this->mesSelecionado)
            ->whereYear('start', $this->anoSelecionado)
            ->get()
            ->sum(fn($a) => $a->service->price ?? 0);

        // total mês anterior
        $totalAnterior = Appointment::with('service')
            ->whereMonth('start', $this->mesSelecionado - 1)
            ->whereYear('start', $this->anoSelecionado)
            ->get()
            ->sum(fn($a) => $a->service->price ?? 0);

        if ($totalAnterior > 0) 
        {
            $variacao = (($totalAtual - $totalAnterior) / $totalAnterior) * 100;
        } else 
        {
            $variacao = 0;
        }

        return $variacao;
    }

    public function soma_dias()
    {
        $numeroDias = Carbon::create($this->anoSelecionado, $this->mesSelecionado)->daysInMonth;
        $valoresPorDia = array_fill(1, $numeroDias, 0);

        // Busca todos os agendamentos do mês com seus serviços
        $agendamentos = Appointment::with('service')
            ->whereMonth('start', $this->mesSelecionado)
            ->whereYear('start', $this->anoSelecionado)
            ->get();

        // Agrupa os valores por dia
        foreach ($agendamentos as $agendamento) {
            $dia = Carbon::parse($agendamento->start)->day;
            $valoresPorDia[$dia] += $agendamento->service->price ?? 0;
        }

        // Converte para Collection, se quiser

        // Exemplo: array do tipo [1 => 100, 2 => 0, 3 => 80, ...]
        return $valoresPorDia;
    }

    public function export_relatory()
    {
        $this->appointments;

        $html = view('pdf.relatory', [
            'appointments' => $this->appointments->toArray(),
        ])->render();

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        now()->setLocale('pt_BR');
        $month = now()->monthName;
        $year = now()->year;
        
        return response()->streamDownload(function () use ($html){
            echo PDF::loadHTML($html)->output();
        }, 'relatorio-' . $month . '-' . $year . '.pdf');
    }
}