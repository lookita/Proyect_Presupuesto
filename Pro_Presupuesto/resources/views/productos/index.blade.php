<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Área de Acciones -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                    <a href="{{ route('productos.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out w-full md:w-auto text-center">
                        {{ __('Crear Nuevo Producto') }}
                    </a>

                    <form method="GET" action="{{ route('productos.index') }}" class="w-full md:w-1/3">
                        <div class="flex items-center">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="{{ __('Buscar por nombre o código...') }}" 
                                value="{{ request('search') }}"
                                class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-l-lg shadow-sm w-full"
                            >
                            <button type="submit" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded-r-lg transition duration-150 ease-in-out border border-gray-300 border-l-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Tabla -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Código') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nombre') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Precio Unitario') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stock') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($productos as $producto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($producto->precio_unitario, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <span class="
                                            px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $producto->stock == 0 ? 'bg-red-100 text-red-800' : ($producto->stock < 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}
                                        ">
                                            {{ $producto->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @can('update', $producto)
                                            <a href="{{ route('productos.edit', $producto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Editar') }}</a>
                                        @endcan

                                        @can('delete', $producto)
                                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar este producto?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Eliminar') }}</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No se encontraron productos.') }}
                                        @if(request('search'))
                                            <p class="mt-1">{{ __('Intenta otra búsqueda o ') }}<a href="{{ route('productos.index') }}" class="text-blue-600 hover:underline">{{ __('limpia el filtro.') }}</a></p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $productos->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
