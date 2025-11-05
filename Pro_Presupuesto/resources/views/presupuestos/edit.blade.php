<x-app-layout>

    <div class="py-12">
        {{-- Se utiliza el mismo ancho de 7xl para mantener la consistencia --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

                <h1 class="text-3xl font-extrabold mb-8 text-center text-indigo-700 border-b pb-4">Editar Presupuesto #{{ $presupuesto->id }}</h1>

                {{-- La acción del formulario ahora apunta a 'update' y usa el método PUT --}}
                <form id="presupuesto-form" action="{{ route('presupuestos.update', $presupuesto) }}" method="POST" data-details='@json($presupuesto->detalles)'>
                    @csrf
                    @method('PUT') 

                    {{-- --- Sección de Cabecera (Cliente, Fecha, Estado) --- --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-gray-50 rounded-lg shadow-inner">
                        <div class="col-span-1">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente:</label>
                            <select name="cliente_id" id="cliente_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-sm">
                                <option value="">Selecciona un cliente...</option>
                                {{-- Precarga del cliente actual --}}
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $presupuesto->cliente_id == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-1">
                            <label for="fecha_emision" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Emisión:</label>
                            {{-- Precarga de la fecha actual --}}
                            <input type="date" id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', $presupuesto->fecha_emision->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fecha_emision') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="col-span-1">
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                            {{-- En edición, el estado sí se puede modificar --}}
                            <select name="estado" id="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @php $estados = ['pendiente', 'aceptado', 'rechazado', 'facturado']; @endphp
                                @foreach($estados as $estado)
                                    <option value="{{ $estado }}" {{ $presupuesto->estado == $estado ? 'selected' : '' }}>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>


                    {{-- --- Detalle del Presupuesto (Tabla) --- --}}
                    <div class="mb-6 border p-4 rounded-lg bg-gray-100 shadow-lg">
                        <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700">Productos del Presupuesto</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-200 text-gray-700">
                                    <tr>
                                        <th class="py-2 px-4 text-left font-semibold w-5/12">Producto/Descripción</th>
                                        <th class="py-2 px-4 text-right font-semibold w-[10%]">Cant.</th>
                                        <th class="py-2 px-4 text-right font-semibold w-[10%]">Desc. (%)</th> {{-- CRÍTICO: Descuento --}}
                                        <th class="py-2 px-4 text-right font-semibold w-[15%]">P. Unitario</th>
                                        <th class="py-2 px-4 text-right font-semibold w-[15%]">Subtotal</th>
                                        <th class="py-2 px-4 w-[5%]"></th> {{-- Columna de acciones --}}
                                    </tr>
                                </thead>
                                {{-- ID CRÍTICO: productos-container --}}
                                <tbody id="productos-container" class="bg-white divide-y divide-gray-100">
                                    {{-- Itera sobre los detalles existentes y los precarga --}}
                                    @foreach($presupuesto->detalles as $index => $detalle)
                                        {{-- IMPORTANTE: Usar la clase 'detalle-row' para que JS lo identifique --}}
                                        <tr class="detalle-row">
                                            <td class="py-2 px-4">
                                                <select name="items[{{ $index }}][producto_id]" 
                                                         class="w-full border-gray-300 rounded-md shadow-sm product-select" required>
                                                     <option value="">Selecciona un producto...</option>
                                                     @foreach($productos as $producto)
                                                         <option value="{{ $producto->id }}" 
                                                                 data-precio="{{ number_format($producto->precio_unitario, 2, '.', '') }}"
                                                                 {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                                             {{ $producto->nombre }}
                                                         </option>
                                                     @endforeach
                                                 </select>
                                                 {{-- Campo oculto para el ID del detalle, si existe (para identificar qué actualizar/eliminar) --}}
                                                 <input type="hidden" name="items[{{ $index }}][id]" value="{{ $detalle->id ?? '' }}">
                                             </td>
                                             <td class="py-2 px-4">
                                                 <input type="number" name="items[{{ $index }}][cantidad]" value="{{ $detalle->cantidad }}" 
                                                         class="w-full border-gray-300 rounded-md shadow-sm text-right quantity-input" min="1" required>
                                             </td>
                                             <td class="py-2 px-4">
                                                 {{-- Se añade el campo de descuento --}}
                                                 <input type="number" name="items[{{ $index }}][descuento_aplicado]" value="{{ $detalle->descuento_aplicado ?? 0 }}" 
                                                         class="w-full border-gray-300 rounded-md shadow-sm text-right discount-input" min="0" max="100">
                                             </td>
                                             <td class="py-2 px-4">
                                                 {{-- Precio unitario. Se hace solo lectura (readonly) si se maneja desde la base de datos --}}
                                                 <input type="number" name="items[{{ $index }}][precio_unitario]" value="{{ number_format($detalle->precio_unitario, 2, '.', '') }}" 
                                                         class="w-full border-gray-300 rounded-md shadow-sm text-right price-input" step="0.01" readonly required>
                                             </td>
                                             {{-- Display del subtotal de la fila --}}
                                             <td class="py-2 px-4 text-right subtotal-display font-semibold">
                                                 ${{ number_format($detalle->subtotal, 2) }}
                                             </td>
                                             <td class="py-2 px-4 text-center">
                                                 <button type="button" class="text-red-600 hover:text-red-900 remove-product text-lg leading-none font-bold">&times;</button>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>

                         {{-- ID CRÍTICO: add-product --}}
                         <button type="button" id="add-product" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-md">
                             + Agregar Producto
                         </button>
                     </div>

                     {{-- --- TEMPLATE PARA NUEVAS FILAS (Oculto) --- --}}
                     {{-- El JS clonará este template --}}
                     <template id="product-row-template">
                         <tr class="detalle-row">
                             <td class="py-2 px-4">
                                 <select name="items[INDEX_PLACEHOLDER][producto_id]" 
                                                 class="w-full border-gray-300 rounded-md shadow-sm product-select" required>
                                         <option value="">Selecciona un producto...</option>
                                         {{-- El JS se encargará de llenar esto con los datos de allProducts --}}
                                     </select>
                                     <input type="hidden" name="items[INDEX_PLACEHOLDER][id]" value="">
                                 </td>
                                 <td class="py-2 px-4">
                                     <input type="number" name="items[INDEX_PLACEHOLDER][cantidad]" value="1" 
                                             class="w-full border-gray-300 rounded-md shadow-sm text-right quantity-input" min="1" required>
                                 </td>
                                 <td class="py-2 px-4">
                                     <input type="number" name="items[INDEX_PLACEHOLDER][descuento_aplicado]" value="0" 
                                             class="w-full border-gray-300 rounded-md shadow-sm text-right discount-input" min="0" max="100">
                                 </td>
                                 <td class="py-2 px-4">
                                     <input type="number" name="items[INDEX_PLACEHOLDER][precio_unitario]" value="0.00" 
                                             class="w-full border-gray-300 rounded-md shadow-sm text-right price-input" step="0.01" readonly required>
                                 </td>
                                 <td class="py-2 px-4 text-right subtotal-display font-semibold">
                                     $0.00
                                 </td>
                                 <td class="py-2 px-4 text-center">
                                     <button type="button" class="text-red-600 hover:text-red-900 remove-product text-lg leading-none font-bold">&times;</button>
                                 </td>
                             </tr>
                         </template>
                         {{-- --- FIN TEMPLATE --- --}}


                         {{-- --- Totales y Campos Ocultos para Laravel --- --}}
                         <div class="flex justify-end mt-8">
                             <div class="w-full max-w-xs p-4 bg-indigo-50 rounded-lg border-2 border-indigo-200">
                                 
                                 {{-- Subtotal Display y Hidden Input (Este será el total Neto después de descuentos) --}}
                                 <div class="text-gray-700 font-semibold text-lg flex justify-between mb-2">
                                     <span>Subtotal Neto:</span> 
                                     {{-- Muestra el subtotal del presupuesto --}}
                                     <span id="subtotal" class="font-normal text-gray-900">${{ number_format($presupuesto->subtotal, 2) }}</span>
                                     {{-- ID y NAME para el envío al backend --}}
                                     <input type="hidden" name="subtotal" id="hidden-subtotal" value="{{ $presupuesto->subtotal }}">
                                 </div>

                                 {{-- Total Display y Hidden Input (En este caso, igual al Subtotal Neto) --}}
                                 <div class="text-gray-900 font-bold text-2xl mt-2 flex justify-between border-t border-indigo-300 pt-2">
                                     <span>TOTAL:</span>
                                     {{-- Muestra el total final del presupuesto --}}
                                     <span id="total" class="font-bold text-indigo-600">${{ number_format($presupuesto->total, 2) }}</span>
                                     {{-- ID y NAME para el envío al backend --}}
                                     <input type="hidden" name="total" id="hidden-total" value="{{ $presupuesto->total }}">
                                 </div>
                             </div>
                         </div>

                         {{-- --- Botón de Actualizar --- --}}
                         <div class="flex justify-end mt-8">
                             <a href="{{ route('presupuestos.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-600 text-xl font-semibold focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-200 shadow-xl mr-4 transform hover:scale-[1.02]">
                                 Cancelar
                             </a>
                             <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl hover:bg-green-700 text-xl font-semibold focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200 shadow-xl transform hover:scale-[1.02]">
                                 Actualizar Presupuesto
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
         
         {{-- Script de inicialización para el JS dinámico --}}
         <script>
             // Inicializa un array global de productos con los datos de Laravel para que el JS los use al agregar nuevas filas
             // Se formatea a JSON para que JS pueda consumirlo.
             const allProducts = @json($productos);

             // Se usa una variable para inicializar el contador de índices del formulario
             // Aseguramos que empiece DEPUÉS de los elementos precargados por PHP
             let itemIndex = {{ count($presupuesto->detalles) }};
         </script>

    @vite('resources/js/presupuestos.js')

 </x-app-layout>
 