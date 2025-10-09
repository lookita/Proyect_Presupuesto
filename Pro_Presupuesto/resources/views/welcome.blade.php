<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('LogoS.png') }}">
    <title>{{ config('app.name', 'Gestor de presupuestos') }}</title>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-bg-light-blue flex justify-center items-center min-h-screen p-4 font-sans text-dark-text">


    <div class="main-container bg-card-bg rounded-app-xl shadow-2xl w-full max-w-6xl overflow-hidden">
        <header class="navbar bg-nav-bg-light px-8 py-6 md:px-10 md:py-8 flex flex-wrap justify-between items-center border-b border-gray-100 shadow-sm-light">
            <div class="logo flex items-center text-lg md:text-xl font-bold text-dark-text mb-4 md:mb-0">
                <img src="LogoS.png" alt="Logo" style="width:40px;height:35px;">
                <span class="logo-text tracking-wide">FINANCIERO</span>
            </div>


            <!-- <nav class="nav-links flex-grow flex justify-center md:justify-start order-3 md:order-none w-full md:w-auto mt-4 md:mt-0">
                <a href="#" class="text-light-text text-sm md:text-base ml-0 md:ml-6 lg:ml-8 hover:text-primary-green transition-colors duration-300 px-2 py-1">Características</a>
                <a href="#" class="text-light-text text-sm md:text-base ml-4 md:ml-6 lg:ml-8 hover:text-primary-green transition-colors duration-300 px-2 py-1">Precios</a>
                <a href="#" class="text-light-text text-sm md:text-base ml-4 md:ml-6 lg:ml-8 hover:text-primary-green transition-colors duration-300 px-2 py-1">Acerca de Nosotros</a>
                <a href="#" class="text-light-text text-sm md:text-base ml-4 md:ml-6 lg:ml-8 hover:text-primary-green transition-colors duration-300 px-2 py-1">Contacto</a>
            </nav> -->


            <div class="auth-buttons flex items-center ml-auto md:ml-0">
                @if (Route::has('login'))
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="btn btn-login text-primary-green border border-primary-green px-4 py-2 rounded-md font-bold text-sm md:text-base ml-2 hover:bg-primary-green hover:text-white transition-colors duration-300"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="btn btn-login text-primary-green border border-primary-green px-4 py-2 rounded-md font-bold text-sm md:text-base ml-2 hover:bg-primary-green hover:text-white transition-colors duration-300"
                        >
                            Iniciar Sesión
                        </a>


                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="btn btn-register bg-primary-green text-white px-4 py-2 rounded-md font-bold text-sm md:text-base ml-2 hover:bg-blue-800 transition-colors duration-300"
                            >
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </header>


        <section class="hero-section px-8 py-10 md:px-10 md:py-12 lg:px-12 lg:py-16 flex flex-col md:flex-row justify-between items-center gap-10 md:gap-12 lg:gap-16 text-center md:text-left">
            <div class="hero-content flex-1">
                <h1 class="text-4xl md:text-5xl lg:text-6xl text-dark-text leading-tight font-extrabold mb-5 md:mb-6 lg:mb-8">
                    Toma el Control de tus Finanzas. <br>Simple y Efectivo.
                </h1>
                <p class="text-base md:text-lg text-light-text leading-relaxed mb-8 md:mb-10 lg:mb-12">
                    Gestiona tus ingresos y gastos, alcanza tus metas financieras y vive sin preocupaciones.
                </p>
                <a href="{{ route('register') }}" class="btn btn-cta inline-block bg-gradient-to-r from-primary-orange to-orange-400 text-white px-8 py-4 rounded-lg text-lg md:text-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300">Empieza Gratis</a>
            </div>
           
            <div class="hero-illustration flex-1 flex justify-center items-center min-h-[300px] md:min-h-[350px] relative">
                <div class="placeholder-image bg-illustration-bg w-full max-w-sm md:max-w-md h-64 md:h-72 rounded-xl flex flex-col justify-center items-center text-light-text italic border-2 border-dashed border-primary-green opacity-70">
                    <img src="/Dashboard.jpeg" alt="Foto modelo de app"/>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
