<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $producto->nombre }}</h3>
                    <p class="text-sm text-gray-600">Código: {{ $producto->codigo }}</p>
                </div>

                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Precio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($producto->precio, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $producto->stock }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('productos.index') }}" class="text-sm text-gray-600 hover:underline">Volver</a>
                    <div>
                        <a href="{{ route('productos.edit', $producto) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">Editar</a>

                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline" onsubmit="return confirmarEliminacion(event);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Eliminar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
function confirmarEliminacion(event) {
    if (!confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>
