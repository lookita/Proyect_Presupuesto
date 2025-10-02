<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-xl border border-gray-100">

                <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Información del Producto</h1>

                <div class="space-y-4 text-gray-700">
                    <div>
                        <span class="font-semibold">Nombre:</span>
                        <span>{{ $producto->nombre }}</span>
                    </div>

                    <div>
                        <span class="font-semibold">Código:</span>
                        <span>{{ $producto->codigo }}</span>
                    </div>

                    <div>
                        <span class="font-semibold">Precio Unitario:</span>
                        <span>${{ number_format($producto->precio, 2) }}</span>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="mt-8 flex justify-end space-x-4">
                    @can('update', $producto)
                        <a href="{{ route('productos.edit', $producto) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Editar
                        </a>
                    @endcan

                    <a href="{{ route('productos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                        Volver
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
