@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="container mx-auto p-8">
    {{-- DÍA 27: FORMULARIO DE BÚSQUEDA Y BOTÓN CREAR: tarea de fran --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">

        {{-- Formulario de Búsqueda --}}
        <form method="GET" action="{{ route('clientes.index') }}" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Buscar</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                    placeholder="Nombre o Email"
                    class="h-10 border border-gray-300 rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="h-10 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="h-10 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-transparent">.</label> {{-- etiqueta vacía para alinear --}}
                <button type="submit"
                    class="h-10 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                    Filtrar
                </button>
            </div>

            <div>
                <label class="block text-sm font-semibold text-transparent">.</label> {{-- etiqueta vacía para alinear --}}
                <a href="{{ route('clientes.index') }}"
                    class="inline-block h-10 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                    Limpiar
                </a>
            </div>
        </form>
        <!-- Día 28: Botón para exportar a PDF -->
        <div class="mb-4 text-right">
            <a href="{{ route('clientes.exportarPDF', request()->all()) }}"
                class="inline-flex items-center justify-center h-10 bg-red-600 text-white px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                📄 Exportar PDF
            </a>
        </div>

        {{-- Botón para Crear Cliente --}}
        <a href="{{ route('clientes.create') }}" class="h-10 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition w-full md:w-auto text-center">
            + Nuevo Cliente
        </a>
    </div>

    {{-- Enlaces de orden dinámico --}}
    <div class="mb-4 flex gap-4 text-sm text-gray-700">
        <span class="font-semibold">Ordenar por:</span>
        <a href="{{ route('clientes.index', ['order_by' => 'nombre', 'direction' => 'asc']) }}" class="text-blue-600 hover:underline">Nombre ↑</a>
        <a href="{{ route('clientes.index', ['order_by' => 'nombre', 'direction' => 'desc']) }}" class="text-blue-600 hover:underline">Nombre ↓</a>
        <a href="{{ route('clientes.index', ['order_by' => 'email', 'direction' => 'asc']) }}" class="text-blue-600 hover:underline">Email ↑</a>
        <a href="{{ route('clientes.index', ['order_by' => 'email', 'direction' => 'desc']) }}" class="text-blue-600 hover:underline">Email ↓</a>
    </div>

    {{-- Mensaje flash mejorado --}}
    @if (session('success'))
    <div class="bg-green-600 text-white p-4 rounded-lg shadow-lg border-l-4 border-green-800 mb-6">
        <strong>✔️ Éxito:</strong> {{ session('success') }}
    </div>
    @endif

    @if (count($clientes) > 0)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado el</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($clientes as $cliente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->codigo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('clientes.show', $cliente->id) }}" class="text-blue-600 hover:text-blue-900 mx-1">Ver</a>
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-yellow-600 hover:text-yellow-900 mx-1">Editar</a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirmarEliminacion(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $clientes->links() }}
    </div>
    @else
    <div class="text-center py-10 bg-white rounded-lg shadow-lg">
        <p class="text-gray-500">No hay clientes registrados.</p>
        @if (request('search'))
        <p class="text-gray-700 font-semibold mt-2">No se encontraron clientes para la búsqueda: "{{ request('search') }}"</p>
        @else
        <p class="text-gray-500 mt-2">¡Comienza a crear uno!</p>
        @endif
    </div>
    @endif
</div>
@endsection

<script>
    function confirmarEliminacion(event) {
        if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>