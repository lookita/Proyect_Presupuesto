<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 max-w-6xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Crear Presupuesto</h1>

        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-10">
            
            <form id="presupuestoForm" action="{{ route('presupuestos.store') }}" method="POST">
                @csrf

                {{-- Errores de Validación --}}
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

                {{-- Cliente y Fecha --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cliente_id') border-red-500 @enderror" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Emisión</label>
                        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('fecha') border-red-500 @enderror" required>
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Notas / Descripción --}}
                <div class="mb-8">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Notas / Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Detalles o condiciones especiales del presupuesto.">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Detalles del Presupuesto --}}
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
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    Haga clic en "Agregar Producto" para comenzar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-product" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out mb-6">
                    + Agregar Producto
                </button>

                {{-- Totales --}}
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-3 p-4 bg-gray-50 rounded-lg border shadow-inner">
                        <div class="flex justify-between font-medium text-gray-700">
                            <span>Subtotal Bruto:</span>
                            <span id="display-subtotal-bruto">$ 0.00</span>
                        </div>
                        <div class="flex justify-between font-medium text-gray-700">
                            <span>Subtotal Neto (con Descuento):</span>
                            <span id="subtotal">$ 0.00</span>
                            <input type="hidden" name="subtotal" id="input-subtotal" value="0.00">
                        </div>
                        <div class="flex justify-between font-bold text-xl text-gray-800 border-t pt-3 mt-3">
                            <span>Total Final:</span>
                            <span id="total">$ 0.00</span>
                            <input type="hidden" name="total" id="input-total" value="0.00">
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
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

    {{-- JS Dinámico --}}
    <script>
        const productosPHP = @json($productos);
        const productosMap = {};
        productosPHP.forEach(p => productosMap[p.id] = { id: p.id, nombre: p.nombre, precio: parseFloat(p.precio) });

        let items = [];
        let itemCounter = 0;

        function addItem() {
            const newItem = { tempId: itemCounter++, producto_id: null, precio_unitario: 0, cantidad: 1, descuento_aplicado: 0, subtotal_bruto: 0, subtotal: 0 };
            items.push(newItem);
            renderItemsTable();
            calculateTotals();
        }

        function removeItem(tempId) {
            items = items.filter(item => item.tempId !== tempId);
            renderItemsTable();
            calculateTotals();
        }

        function updateItem(tempId, field, value) {
            const index = items.findIndex(i => i.tempId === tempId);
            if (index === -1) return;

            if (['cantidad', 'descuento_aplicado', 'precio_unitario'].includes(field)) value = parseFloat(value) || 0;
            items[index][field] = value;

            if (field === 'producto_id' && productosMap[value]) {
                items[index].precio_unitario = productosMap[value].precio;
                document.getElementById(`precio_unitario_${tempId}`).value = productosMap[value].precio.toFixed(2);
            }

            calculateItemSubtotal(index);
            calculateTotals();
        }

        function calculateItemSubtotal(index) {
            const item = items[index];
            item.subtotal_bruto = item.precio_unitario * item.cantidad;
            item.subtotal = item.subtotal_bruto * (1 - Math.min(Math.max(item.descuento_aplicado,0),100)/100);

            const display = document.getElementById(`subtotal_display_${item.tempId}`);
            if (display) display.textContent = `$ ${item.subtotal.toFixed(2)}`;
        }

        function calculateTotals() {
            const totalBruto = items.reduce((sum,i)=>sum+i.subtotal_bruto,0);
            const totalNeto = items.reduce((sum,i)=>sum+i.subtotal,0);

            document.getElementById('display-subtotal-bruto').textContent = `$ ${totalBruto.toFixed(2)}`;
            document.getElementById('subtotal').textContent = `$ ${totalNeto.toFixed(2)}`;
            document.getElementById('total').textContent = `$ ${totalNeto.toFixed(2)}`;

            document.getElementById('input-subtotal').value = totalNeto.toFixed(2);
            document.getElementById('input-total').value = totalNeto.toFixed(2);
        }

        function renderItemsTable() {
            const tbody = document.getElementById('items-table-body');
            tbody.innerHTML = '';
            if(items.length===0){
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">Haga clic en "Agregar Producto" para comenzar.</td></tr>`;
                return;
            }

            items.forEach((item,index)=>{
                const row = tbody.insertRow();
                row.className = 'hover:bg-gray-50';

                row.innerHTML = `
                    <td class="px-3 py-2">
                        <select name="items[${index}][producto_id]" onchange="updateItem(${item.tempId},'producto_id',this.value)" class="w-full border-gray-300 rounded-lg p-2" required>
                            <option value="">Seleccionar</option>
                            ${Object.values(productosMap).map(p=>`<option value="${p.id}" ${item.producto_id==p.id?'selected':''}>${p.nombre}</option>`).join('')}
                        </select>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0" name="items[${index}][precio_unitario]" id="precio_unitario_${item.tempId}" value="${item.precio_unitario.toFixed(2)}" oninput="updateItem(${item.tempId},'precio_unitario',this.value)" class="w-full text-right p-2 border-gray-300 rounded-lg" required>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="1" name="items[${index}][cantidad]" value="${item.cantidad}" oninput="updateItem(${item.tempId},'cantidad',this.value)" class="w-full text-right p-2 border-gray-300 rounded-lg" required>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="0" max="100" step="1" name="items[${index}][descuento_aplicado]" value="${item.descuento_aplicado}" oninput="updateItem(${item.tempId},'descuento_aplicado',this.value)" class="w-full text-right p-2 border-gray-300 rounded-lg">
                    </td>
                    <td class="px-3 py-2 text-right font-semibold text-sm">
                        <span id="subtotal_display_${item.tempId}">$ ${item.subtotal.toFixed(2)}</span>
                        <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal.toFixed(2)}">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" onclick="removeItem(${item.tempId})" class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-50">
                            ✕
                        </button>
                    </td>
                `;
            });
        }

        window.onload = function(){
            document.getElementById('add-product').addEventListener('click', addItem);
            if(items.length===0) addItem();
        };
    </script>
</x-app-layout>