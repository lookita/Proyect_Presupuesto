<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Presupuesto') }} #{{ $presupuesto->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-xl">

                <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Editar Presupuesto #{{ $presupuesto->id }}</h1>

                {{-- La acción del formulario ahora apunta a 'update' --}}
                <form action="{{ route('presupuestos.update', $presupuesto) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Importante para indicar que es una actualización --}}

                    {{-- --- Selección de Cliente --- --}}
                    <div class="mb-6">
                        <label for="cliente_id" class="block text-gray-700 font-semibold mb-2">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-sm">
                            <option value="">Selecciona un cliente...</option>
                            @foreach($clientes as $cliente)
                                {{-- Pre-selecciona el cliente actual del presupuesto --}}
                                <option value="{{ $cliente->id }}" {{ $presupuesto->cliente_id == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
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
                                    {{-- Iterar sobre los detalles existentes del presupuesto --}}
                                    @foreach($presupuesto->detalles as $index => $detalle)
                                        <tr class="product-item">
                                            <td class="py-2 px-4">
                                                <select name="detalles[{{ $index }}][producto_id]" class="w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="">Selecciona un producto...</option>
                                                    @foreach($productos as $producto)
                                                        <option value="{{ $producto->id }}" 
                                                                data-precio="{{ $producto->precio }}"
                                                                {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                                            {{ $producto->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="py-2 px-4">
                                                <input type="number" name="detalles[{{ $index }}][cantidad]" value="{{ $detalle->cantidad }}" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm quantity-input" min="1">
                                            </td>
                                            <td class="py-2 px-4 text-right">
                                                <input type="number" name="detalles[{{ $index }}][precio_unitario]" value="{{ $detalle->precio_unitario }}" 
                                                       class="w-full border-gray-300 rounded-md shadow-sm price-input" step="0.01" readonly>
                                            </td>
                                            <td class="py-2 px-4 text-right subtotal-display">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                                            <td class="py-2 px-4 text-center">
                                                <button type="button" class="text-red-600 hover:text-red-900 remove-product">&times;</button>
                                            </td>
                                        </tr>
                                    @endforeach
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
                            <input type="hidden" name="total" id="hidden_total_input">
                        </div>
                    </div>

                    {{-- --- Botón de Actualizar --- --}}
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 text-xl font-semibold focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200 shadow-xl">
                            Actualizar Presupuesto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para gestionar productos dinámicamente (similar al de 'create') --}}
    <script>
        // Aquí iría tu JS para agregar/eliminar filas y calcular totales
        // Necesitarás los productos disponibles pasados desde el controlador.
        const allProducts = @json($productos); // Asumiendo que pasas $productos desde el controlador
        // ... (resto de tu lógica JS para el formulario dinámico) ...
    </script>
</x-app-layout>