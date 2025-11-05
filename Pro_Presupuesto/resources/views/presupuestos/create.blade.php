<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 max-w-6xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Crear Presupuesto</h1>

        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-10">
            
            {{-- Formulario Principal --}}
            <form id="presupuestoForm" action="{{ route('presupuestos.store') }}" method="POST">
                @csrf
                
                {{-- SECCIÓN DE ERRORES DE VALIDACIÓN --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <p class="font-bold mb-2">Por favor, corrija los siguientes errores:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Campo Cliente --}}
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <select 
                            name="cliente_id" 
                            id="cliente_id" 
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cliente_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option 
                                    value="{{ $cliente->id }}" 
                                    {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}
                                >
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Fecha de Emisión --}}
                    <div>
                        <label for="fecha_emision" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Emisión</label>
                        <input 
                            type="date" 
                            name="fecha_emision" 
                            id="fecha_emision" 
                            value="{{ old('fecha_emision', date('Y-m-d')) }}" 
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('fecha_emision') border-red-500 @enderror"
                            required
                        >
                        @error('fecha_emision')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Campo Notas / Descripción --}}
                <div class="mb-8">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Notas / Descripción</label>
                    <textarea 
                        name="descripcion" 
                        id="descripcion" 
                        rows="3"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Detalles o condiciones especiales del presupuesto."
                    >{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TABLA DE ÍTEMS DINÁMICOS --}}
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Detalles del Presupuesto</h2>
                
                <div class="overflow-x-auto mb-6 bg-gray-50 p-2 md:p-4 rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Producto</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Precio Unitario</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Cantidad</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Desc. (%)</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Subtotal</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body" class="bg-white divide-y divide-gray-200">
                            {{-- Las filas de productos se inyectarán aquí con JavaScript --}}
                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-product" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out mb-6">
                    + Agregar Producto
                </button>

                {{-- Totales y Botón de Envío --}}
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-3 p-4 bg-gray-50 rounded-lg border shadow-inner">
                        <div class="flex justify-between font-medium text-gray-700">
                            <span>Subtotal Bruto:</span>
                            <span id="display-subtotal-bruto">$ 0.00</span>
                            {{-- Hidden input for the gross subtotal (price * quantity) if needed for later calculations --}}
                            {{-- <input type="hidden" name="subtotal_bruto" id="input-subtotal-bruto" value="0.00"> --}}
                        </div>
                        <div class="flex justify-between font-medium text-gray-700">
                            <span>Subtotal Neto (con Descuento):</span>
                            <span id="subtotal">$ 0.00</span> 
                            {{-- Se usa 'subtotal' para coincidir con el JS, aunque el input se llama 'input-subtotal' --}}
                            <input type="hidden" name="subtotal" id="input-subtotal" value="0.00">
                        </div>
                        <div class="flex justify-between font-bold text-xl text-gray-800 border-t pt-3 mt-3">
                            <span>Total Final:</span>
                            <span id="total">$ 0.00</span> 
                            {{-- Se usa 'total' para coincidir con el JS, aunque el input se llama 'input-total' --}}
                            <input type="hidden" name="total" id="input-total" value="0.00">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <a href="{{ route('presupuestos.index') }}" class="inline-flex items-center justify-center mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-lg transition duration-200 ease-in-out">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-xl transition duration-200 ease-in-out text-lg">
                        Guardar Presupuesto
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LÓGICA DE JAVASCRIPT PARA LA TABLA DINÁMICA --}}
    <script>
        // 1. Datos iniciales del servidor
        // Transformar la colección de productos de PHP a un objeto JS para un acceso rápido por ID.
        const productosPHP = @json($productos);
        const productosMap = {};
        productosPHP.forEach(p => {
            // Aseguramos que el precio sea un número flotante
            productosMap[p.id] = { id: p.id, nombre: p.nombre, precio: parseFloat(p.precio) };
        });

        // Array para mantener el estado de los ítems del presupuesto
        let items = [];
        let itemCounter = 0; // Usado para dar un ID temporal único a cada fila

        /**
         * Añade una nueva fila de producto al array de ítems y actualiza la tabla.
         */
        function addItem() {
            const newItem = {
                // ID temporal para JS.
                tempId: itemCounter++, 
                producto_id: null,
                precio_unitario: 0.00,
                cantidad: 1,
                descuento_aplicado: 0, // <- CLAVE CORREGIDA
                subtotal_bruto: 0.00, // Precio * Cantidad (sin descuento)
                subtotal: 0.00,       // Precio * Cantidad con descuento aplicado
            };
            items.push(newItem);
            renderItemsTable();
            calculateTotals();
        }

        /**
         * Elimina una fila de producto por su ID temporal.
         */
        function removeItem(tempId) {
            items = items.filter(item => item.tempId !== tempId);
            renderItemsTable();
            calculateTotals();
        }

        /**
         * Actualiza un ítem en el array basado en la entrada del usuario.
         */
        function updateItem(tempId, field, value) {
            const index = items.findIndex(item => item.tempId === tempId);
            if (index === -1) return;

            // Asegurarse de que los valores numéricos sean números y manejen campos vacíos o nulos
            if (['cantidad', 'descuento_aplicado', 'precio_unitario'].includes(field)) {
                // Convertir a número flotante o 0 si está vacío
                items[index][field] = parseFloat(value) || 0;
            } else {
                items[index][field] = value;
            }

            // Lógica especial para cambio de producto: cargar precio unitario
            if (field === 'producto_id' && productosMap[value]) {
                const product = productosMap[value];
                items[index].precio_unitario = product.precio;
                // Actualizar el campo de texto de precio unitario en la UI
                document.getElementById(`precio_unitario_${tempId}`).value = product.precio.toFixed(2);
            }

            calculateItemSubtotal(index);
            calculateTotals();
        }

        /**
         * Calcula el subtotal (bruto y neto) de un ítem.
         */
        function calculateItemSubtotal(index) {
            let item = items[index];
            const precio = item.precio_unitario;
            const cantidad = item.cantidad;
            const descuento = item.descuento_aplicado;

            let subtotalBruto = precio * cantidad;
            let subtotalNeto = subtotalBruto;
            
            // Aplicar descuento
            if (descuento > 0 && descuento <= 100) {
                subtotalNeto = subtotalBruto * (1 - (descuento / 100));
            }

            item.subtotal_bruto = subtotalBruto;
            item.subtotal = subtotalNeto;

            // Actualizar el display del subtotal de la fila
            const subtotalDisplay = document.getElementById(`subtotal_display_${item.tempId}`);
            if (subtotalDisplay) {
                subtotalDisplay.textContent = `$ ${item.subtotal.toFixed(2)}`;
            }
        }

        /**
         * Recalcula el Subtotal Neto y Total general del presupuesto.
         */
        function calculateTotals() {
            // Suma de los subtotales netos (con descuento) de cada ítem
            let totalSubtotalNeto = items.reduce((sum, item) => sum + item.subtotal, 0);

            // Suma de los subtotales brutos (sin descuento)
            let totalSubtotalBruto = items.reduce((sum, item) => sum + item.subtotal_bruto, 0);

            // Supongamos que el Total Final es el Subtotal Neto por ahora (sin IVA, etc.)
            let totalFinal = totalSubtotalNeto; 

            // Actualizar los displays (usando IDs del HTML)
            document.getElementById('display-subtotal-bruto').textContent = `$ ${totalSubtotalBruto.toFixed(2)}`;
            document.getElementById('subtotal').textContent = `$ ${totalSubtotalNeto.toFixed(2)}`;
            document.getElementById('total').textContent = `$ ${totalFinal.toFixed(2)}`;

            // Actualizar los inputs hidden para el envío del formulario
            document.getElementById('input-subtotal').value = totalSubtotalNeto.toFixed(2);
            document.getElementById('input-total').value = totalFinal.toFixed(2);
        }

        /**
         * Dibuja (o redibuja) la tabla de ítems completa.
         */
        function renderItemsTable() {
            const tbody = document.getElementById('items-table-body');
            tbody.innerHTML = '';
            
            items.forEach((item, index) => {
                const newRow = tbody.insertRow();
                newRow.className = 'hover:bg-gray-50';

                // Columna 1: Producto (Select)
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap">
                        <select 
                            name="items[${index}][producto_id]" 
                            onchange="updateItem(${item.tempId}, 'producto_id', this.value)"
                            class="w-full border-gray-300 rounded-lg text-sm p-2 @error('items.${index}.producto_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccionar</option>
                            ${Object.values(productosMap).map(p => `
                                <option value="${p.id}" ${item.producto_id == p.id ? 'selected' : ''}>
                                    ${p.nombre}
                                </option>
                            `).join('')}
                        </select>
                    </td>
                `;

                // Columna 2: Precio Unitario
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap">
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0"
                            name="items[${index}][precio_unitario]" 
                            id="precio_unitario_${item.tempId}"
                            value="${item.precio_unitario.toFixed(2)}"
                            oninput="updateItem(${item.tempId}, 'precio_unitario', this.value)"
                            class="w-full border-gray-300 rounded-lg text-sm text-right p-2 @error('items.${index}.precio_unitario') border-red-500 @enderror"
                            required
                        />
                    </td>
                `;

                // Columna 3: Cantidad
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap">
                        <input 
                            type="number" 
                            min="1"
                            name="items[${index}][cantidad]" 
                            value="${item.cantidad}"
                            oninput="updateItem(${item.tempId}, 'cantidad', this.value)"
                            class="w-full border-gray-300 rounded-lg text-sm text-right p-2 @error('items.${index}.cantidad') border-red-500 @enderror"
                            required
                        />
                    </td>
                `;
                
                // Columna 4: Descuento (%)
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap">
                        <input 
                            type="number" 
                            min="0"
                            max="100"
                            step="1"
                            name="items[${index}][descuento_aplicado]" 
                            value="${item.descuento_aplicado}"
                            oninput="updateItem(${item.tempId}, 'descuento_aplicado', this.value)"
                            class="w-full border-gray-300 rounded-lg text-sm text-right p-2 @error('items.${index}.descuento_aplicado') border-red-500 @enderror"
                        />
                    </td>
                `;

                // Columna 5: Subtotal de fila (Solo display)
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap text-right font-semibold text-sm">
                        <span id="subtotal_display_${item.tempId}">$ ${item.subtotal.toFixed(2)}</span>
                        {{-- También enviamos el subtotal final del ítem, aunque no es estrictamente necesario si se calcula en el backend --}}
                        <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal.toFixed(2)}">
                    </td>
                `;
                
                // Columna 6: Acción (Botón Eliminar)
                newRow.innerHTML += `
                    <td class="px-3 py-2 whitespace-nowrap text-center">
                        <button type="button" onclick="removeItem(${item.tempId})" class="text-red-600 hover:text-red-800 transition duration-150 ease-in-out p-1 rounded-full hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;
            });
            
            // Si no hay ítems, añade un mensaje
            if (items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Haga clic en "Agregar Producto" para comenzar.
                        </td>
                    </tr>
                `;
            }
        }

        // Inicializar la tabla y los totales al cargar la página
        window.onload = function() {
            // Asignar el listener al botón de agregar producto
            document.getElementById('add-product').addEventListener('click', addItem);
            
            // Si es la vista de creación, agrega una fila inicial si el array de items está vacío
            if (items.length === 0 && document.getElementById('items-table-body').children.length === 0) {
                addItem();
            } else {
                renderItemsTable(); 
                calculateTotals(); 
            }
        };
    </script>
</x-app-layout>