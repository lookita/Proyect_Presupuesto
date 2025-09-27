<x-app-layout>
    {{-- Slot para el encabezado (si lo usas en app.blade.php) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Presupuesto') }}
        </h2>
    </x-slot>

    {{-- Contenedor principal de la vista --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-xl">

                <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Crear Nuevo Presupuesto</h1>

                <form action="{{ route('presupuestos.store') }}" method="POST">
                    @csrf

                    {{-- --- Selección de Cliente --- --}}
                    <div class="mb-6">
                        <label for="cliente_id" class="block text-gray-700 font-semibold mb-2">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-sm">
                            <option value="">Selecciona un cliente...</option>
                            {{-- TODO: Iterar sobre la colección $clientes aquí: --}}
                            {{-- @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach --}}
                        </select>
                        @error('cliente_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- --- Detalle del Presupuesto (Tabla) --- --}}
                    <div class="mb-6 border p-4 rounded-lg bg-gray-50">
                        <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700">Detalle de Productos</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead class="bg-gray-200 text-gray-700">
                                    <tr>
                                        <th class="py-2 px-4 text-left font-semibold">Producto/Descripción</th>
                                        <th class="py-2 px-4 text-left font-semibold w-24">Cantidad</th>
                                        <th class="py-2 px-4 text-right font-semibold w-32">P. Unitario</th>
                                        <th class="py-2 px-4 text-right font-semibold w-32">Subtotal</th>
                                        <th class="py-2 px-4 w-12"></th> {{-- Columna de acciones --}}
                                    </tr>
                                </thead>
                                <tbody id="productos-container">
                                    {{-- Filas de productos inyectadas por JS/Livewire --}}
                                </tbody>
                            </table>
                        </div>

                        <button type="button" id="add-product" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-md">
                            Agregar Producto
                        </button>
                    </div>

                    {{-- --- Totales --- --}}
                    <div class="mt-8 text-right pr-4 border-t pt-4">
                        <div class="text-gray-700 font-semibold text-lg">
                            Subtotal: <span id="subtotal" class="font-normal text-gray-900">$0.00</span>
                        </div>
                        <div class="text-gray-900 font-bold text-2xl mt-2">
                            Total: <span id="total" class="font-normal text-indigo-600">$0.00</span>
                            <input type="hidden" name="total" id="hidden_total_input"> {{-- Campo oculto para enviar el total --}}
                        </div>
                    </div>

                    {{-- --- Botón de Guardar --- --}}
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 text-xl font-semibold focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-xl">
                            Guardar Presupuesto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>