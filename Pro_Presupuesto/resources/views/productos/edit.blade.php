<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('productos.update', $producto) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <input type="text" name="codigo" value="{{ old('codigo', $producto->codigo) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('precio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $producto->stock) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('stock') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('productos.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                        <div>
                            <a href="{{ route('productos.show', $producto) }}" class="text-sm text-gray-600 hover:underline mr-3">Ver</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Actualizar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
