<x-app-layout>

    <div class="py-8">
        {{-- Aumentamos el ancho para un mejor layout del contenido --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

                <div class="flex justify-between items-center border-b pb-2 mb-6">
                    <h1 class="text-3xl font-extrabold text-indigo-700">
                        Presupuesto #{{ $presupuesto->id }}
                    </h1>
                    <a href="{{ route('presupuestos.edit', $presupuesto) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md flex items-center">
                        Editar
                    </a>
                </div>

                {{-- --- Sección de Información General --- --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-base mb-8 p-4 bg-gray-50 rounded-lg shadow-inner">
                    <div>
                        <p class="text-gray-600 font-medium">Cliente:</p>
                        {{-- Asumimos que la relación está cargada --}}
                        <p class="font-extrabold text-gray-900 text-xl">{{ $presupuesto->cliente->nombre }}</p>
                        <p class="text-gray-700 text-sm">{{ $presupuesto->cliente->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 font-medium">Fecha de Emisión:</p>
                        {{-- Usamos la fecha de emisión del presupuesto, no la de creación --}}
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 font-medium">Estado:</p>
                        @php
                            $color = [
                                'facturado' => 'bg-green-100 text-green-800',
                                'aceptado' => 'bg-blue-100 text-blue-800',
                                'rechazado' => 'bg-red-100 text-red-800',
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                            ][$presupuesto->estado] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span 
                            class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $color }} uppercase tracking-wide">
                            {{ ucfirst($presupuesto->estado) }}
                        </span>
                    </div>
                </div>

                {{-- --- Sección de Detalles / Productos --- --}}
                <h2 class="text-2xl font-bold mt-8 mb-4 text-gray-800 border-b pb-2">Detalles del Presupuesto</h2>
                
                <div class="overflow-x-auto shadow-xl rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th class="py-3 px-4 text-left">Producto</th>
                                <th class="py-3 px-4 text-center">Cantidad</th>
                                <th class="py-3 px-4 text-right">P. Unitario</th>
                                <th class="py-3 px-4 text-right">Desc. (%)</th>
                                {{-- Subtotal del ítem, ya con descuento --}}
                                <th class="py-3 px-4 text-right">Subtotal Ítem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($presupuesto->detalles as $detalle)
                            <tr class="hover:bg-indigo-50/20">
                                {{-- Asumimos que la relación producto está cargada --}}
                                <td class="py-3 px-4 text-sm font-medium text-gray-900">{{ $detalle->producto->nombre }}</td>
                                <td class="py-3 px-4 text-center text-sm text-gray-700">{{ $detalle->cantidad }}</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-700">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="py-3 px-4 text-right text-sm text-red-600 font-medium">{{ $detalle->descuento_aplicado ?? 0 }}%</td>
                                <td class="py-3 px-4 text-right text-sm font-bold text-gray-900">
                                    {{-- El subtotal debe ser el campo 'subtotal' de la tabla de detalle, ya calculado con el descuento --}}
                                    ${{ number_format($detalle->subtotal, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- --- Total General (Usando los campos almacenados) --- --}}
                <div class="mt-8 pt-4 border-t border-gray-300 text-right">
                    <div class="text-xl font-semibold text-gray-700 mb-2">
                        Subtotal (bruto): 
                        <span class="font-medium text-gray-900 ml-2">
                             ${{ number_format($presupuesto->subtotal, 2) }}
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-gray-900">
                        TOTAL FINAL: 
                        <span class="text-indigo-600 ml-2">
                             ${{ number_format($presupuesto->total, 2) }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
