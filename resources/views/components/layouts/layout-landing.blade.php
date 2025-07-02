<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Barber Flow' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-base-100 text-base-content min-h-screen m-0 p-0" style="background-color: #CEAD77;">
    {{ $slot }}
    @livewireScripts
</body>
</html>