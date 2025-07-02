<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Agendamento</title>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .email-wrapper {
            max-width: 650px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .header {
            background-color: #4A90E2;
            color: white;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .content h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .content p {
            font-size: 16px;
            margin-bottom: 16px;
        }

        .service-box {
            background-color: #f9f9f9;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .service-box h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #4A90E2;
        }

        .service-box p {
            margin: 4px 0;
            font-size: 15px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color: #333;
        }

        .footer {
            background-color: #f2f2f2;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }

        .footer p {
            margin: 0;
        }

        .emoji {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Cancelamento de Agendamento</h1>
            {{ ds($appointment) }}
        </div>

        <div class="content">
            <h2>Olá, {{ $appointment->user->name }} 👋</h2>
            <p>Que pena, seu agendamento foi <strong>cancelado</strong>! Aqui estão os detalhes:</p>

            @php $total = 0;
                $date = \Carbon\Carbon::parse($appointment->start)->translatedFormat('d \d\e F \d\e Y');
                $start = \Carbon\Carbon::parse($appointment->start)->format('H:i');
                $end = \Carbon\Carbon::parse($appointment->end)->format('H:i');
                $price = number_format($appointment->service->price, 2, ',', '.');
                $total += $appointment->service->price;
            @endphp

            <div class="service-box">
                <h3>💇‍♂️ Serviço: {{ $appointment->service->name }}</h3>
                <p><strong>📅 Data:</strong> {{ $date }}</p>
                <p><strong>⏰ Horário:</strong> {{ $start }} às {{ $end }}</p>
                <p><strong>💰 Preço:</strong> R$ {{ $price }}</p>
                <p><strong>👤 Profissional:</strong> {{ $appointment->barber->name }}</p>
            </div>

            <p class="total">🧾 Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>🙏 Agradecemos pela preferência! Nos vemos em breve. ✂️</p>
        </div>
    </div>
</body>
</html>
