<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Presupuestos</title>

    <!-- Tailwind y scripts compilados -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    <!-- Barra de navegación -->
    @include('layouts.nav')

    <!-- Contenido de cada vista -->
    <div class="container mx-auto p-4">
        @yield('content')
    </div>

</body>
</html>
