<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <script src="//unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Calendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <script src="https://unpkg.com/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <link href="https://unpkg.com/flatpickr/dist/plugins/monthSelect/style.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand/>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

                {{-- BRAND --}}
                <x-app-brand class="p-5 pt-3" />

                {{-- MENU --}}
                <x-menu activate-by-route>

                    {{-- User --}}
                    @if($user = auth()->user())
                        <x-menu-separator />                    
                        <x-list-item :item="$user" value="name" sub-value="email" no-separator link="/profile"  class="-mx-2 !-my-2 rounded">
                            <x-slot:actions>
                                <x-button 
                                    icon="o-power" 
                                    class="btn-circle btn-ghost btn-xs" 
                                    tooltip-left="sair" 
                                    link="/logout"
                                />
                            </x-slot:actions>
                        </x-list-item>
                        <x-menu-separator />
                    @endif

                    <x-menu-item 
                        title="Tema" 
                        icon="o-swatch" 
                        @click="$dispatch('mary-toggle-theme')" 
                    />

                    @can('admin',auth()->user())
                        <x-menu-item 
                            title="Financeiro" 
                            icon="o-currency-dollar" 
                            link="/finance" 
                        />

                        <x-menu-item 
                            title="Usuarios" 
                            icon="o-user-circle" 
                            link="/users" 
                        />

                        <x-menu-item 
                            title="Serviços" 
                            icon="o-rectangle-stack" 
                            link="/services" 
                        />

                        <x-menu-item 
                            title="Agendamentos" 
                            icon="o-check-badge" 
                            link="/calendar" 
                        />
                    @endcan  
                    
                    <x-menu-item 
                        title="Marcações" 
                        icon="o-calendar-days" 
                        link="/appointments" 
                    />

                    <x-menu-item 
                            title="Usuário"
                            icon="o-identification"
                            link="/profile"
                        />
                </x-menu>
                

                <x-theme-toggle class="hidden" />
            </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

    @livewireScripts
    @stack('scripts')
</body>
</html>
