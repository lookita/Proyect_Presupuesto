<!-- resources/views/layouts/nav.blade.php -->
<nav class="bg-blue-600 text-white p-4 flex gap-6">
    <a href="{{ route('clientes.index') }}" class="hover:underline">Clientes</a>
    <a href="{{ route('productos.index') }}" class="hover:underline">Productos</a>
    <a href="{{ route('presupuestos.index') }}" class="hover:underline">Presupuestos</a>
</nav>
