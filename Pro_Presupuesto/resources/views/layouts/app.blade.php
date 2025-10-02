<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('LogoS.png') }}">
        <title>{{ config('app.name', 'Gestor de presuestos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Día 15 de mensaje flash -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto mt-4">
                    <div class="bg-green-500 text-white p-3 rounded-md shadow">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="max-w-7xl mx-auto mt-4">
                    <div class="bg-red-500 text-white p-3 rounded-md shadow">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{-- 
                    CORRECCIÓN CLAVE: Se añade el chequeo de existencia de $slot y la directiva @yield('content').
                    Esto permite que el layout funcione tanto con componentes de Blade (que usan $slot) 
                    como con la sintaxis tradicional @extends / @section('content').
                --}}
                @isset($slot)
                    {{ $slot }}
                @endisset

                @yield('content')
            </main>
        </div>
    </body>
</html>
