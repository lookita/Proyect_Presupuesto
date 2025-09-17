<!-- resources/views/components/nav.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Proyecto</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('presupuestos.index') }}">Presupuestos</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('servicios.index') }}">Servicios</a></li>
        </ul>
    </div>
</nav>
