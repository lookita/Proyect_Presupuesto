<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-xl border border-gray-100">

                <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Editar Producto</h1>

                <form action="{{ route('productos.update', $producto) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 @error('nombre') border-red-500 @enderror"
                               required maxlength="100">
                        @error('nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Código --}}
                    <div class="mb-4">
                        <label for="codigo" class="block text-gray-700 font-semibold mb-2">Código</label>
                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 @error('codigo') border-red-500 @enderror"
                               required maxlength="50">
                        @error('codigo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Precio --}}
                    <div class="mb-6">
                        <label for="precio" class="block text-gray-700 font-semibold mb-2">Precio</label>
                        <input type="number" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 @error('precio') border-red-500 @enderror"
                               step="0.01" required min="0.01">
                        @error('precio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 focus:ring-indigo-300 transition">
                            Actualizar Producto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
