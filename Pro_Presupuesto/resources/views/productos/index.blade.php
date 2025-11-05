@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
<div class="container mx-auto p-8">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        
        <form method="GET" action="{{ route('productos.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="search" class="block text-sm font-semibold text-gray-700">Buscar Producto</label>
                <input type="text" name="search" id="search" value="{{ $search }}"
                    placeholder="Buscar por nombre..."
                    class="h-10 border border-gray-300 rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="h-10 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                Filtrar
            </button>
            
            @if ($search)
                <a href="{{ route('productos.index') }}"
                    class="inline-block h-10 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none text-center leading-6">
                    Limpiar
                </a>
            @endif
        </form>

        {{-- Botón para Crear Producto --}}
        <a href="{{ route('productos.create') }}" class="h-10 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition w-full md:w-auto text-center">
            + Nuevo Producto
        </a>
    </div>

    @if (count($productos) > 0)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($productos as $producto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $producto->codigo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($producto->precio, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $producto->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('productos.show', $producto->id) }}" class="text-blue-600 hover:text-blue-900 mx-1">Ver</a>
                            <a href="{{ route('productos.edit', $producto->id) }}" class="text-yellow-600 hover:text-yellow-900 mx-1">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" onsubmit="return confirmarEliminacion(event)" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
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
        @if(method_exists($productos, 'links'))
            {{ $productos->appends(['search' => $search])->links() }}
        @endif
    </div>
    @else
    <div class="text-center py-10 bg-white rounded-lg shadow-lg">
        <p class="text-gray-500">No hay productos registrados.</p>
        @if ($search)
        <p class="text-gray-700 font-semibold mt-2">No se encontraron productos para la búsqueda: "{{ $search }}"</p>
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