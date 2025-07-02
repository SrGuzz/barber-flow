<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Agendamentos</title>
    <style>
        /* -----  Configurações de página para geradores de PDF (ex.: dompdf)  ----- */
        @page {
            size: A4 landscape;
            margin: 2cm 2.5cm;
        }

        /* -----  Estilo global  ----- */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* -----  Cabeçalho  ----- */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* -----  Metadados (data de geração, etc.)  ----- */
        .meta {
            text-align: right;
            margin-bottom: 10px;
            font-size: 10px;
            color: #666;
        }

        /* -----  Tabela de agendamentos  ----- */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px 8px;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        .confirmed {
            color: #28a745; /* verde */
            font-weight: bold;
        }
        .pending {
            color: #dc3545; /* vermelho */
            font-weight: bold;
        }

        /* -----  Rodapé com número de páginas  ----- */
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        /* dompdf permite counters CSS */
        .footer::after {
            content: "Página " counter(page) " de " counter(pages);
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>Relatório Financeiro de Agendamentos do mês de {{ \Carbon\Carbon::parse($appointments[0]['start'])->monthName }}</h1>
    </div>

    <!-- Metadados -->
    <div class="meta">
        Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <!-- Tabela principal -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Barbeiro</th>
                <th>Serviço</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $index => $appointment)
                @php
                    $isConfirmed = !empty($appointment['confirmed_at']);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $appointment['user_id'] }}</td>
                    <td>{{ $appointment['barber_id'] }}</td>
                    <td>{{ $appointment['service_id'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment['start'])->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment['end'])->format('d/m/Y H:i') }}</td>
                    <td class="{{ $isConfirmed ? 'confirmed' : 'pending' }}">
                        {{ $isConfirmed ? 'Confirmado' : 'Pendente' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Rodapé -->
    <div class="footer"></div>
</body>
</html>
