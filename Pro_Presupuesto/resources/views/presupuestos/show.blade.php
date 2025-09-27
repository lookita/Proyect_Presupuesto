<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Presupuesto') }} #{{ $presupuesto->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

                <h1 class="text-3xl font-extrabold mb-6 text-indigo-700 border-b pb-2">
                    Presupuesto #{{ $presupuesto->id }}
                </h1>

                {{-- --- Sección de Información General --- --}}
                <div class="grid grid-cols-2 gap-4 text-lg mb-6">
                    <div>
                        <p class="text-gray-600">Cliente:</p>
                        <p class="font-semibold text-gray-900">{{ $presupuesto->cliente->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Fecha de Creación:</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($presupuesto->created_at)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Estado:</p>
                        <span 
                            class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full
                                {{ $presupuesto->estado == 'facturado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $presupuesto->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $presupuesto->estado == 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                            {{ ucfirst($presupuesto->estado) }}
                        </span>
                    </div>
                </div>

                {{-- --- Sección de Detalles / Productos --- --}}
                <h2 class="text-2xl font-bold mt-8 mb-4 text-gray-800 border-b pb-2">Detalles del Presupuesto</h2>
                
                <div class="overflow-x-auto shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th class="py-3 px-4 text-left">Producto</th>
                                <th class="py-3 px-4 text-center">Cantidad</th>
                                <th class="py-3 px-4 text-right">Precio Unitario</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($presupuesto->detalles as $detalle)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-900">{{ $detalle->producto->nombre }}</td>
                                <td class="py-3 px-4 text-center text-sm text-gray-700">{{ $detalle->cantidad }}</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-700">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="py-3 px-4 text-right text-sm font-medium text-gray-900">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- --- Total General --- --}}
                <div class="mt-8 pt-4 border-t border-gray-200 text-right">
                    <p class="text-3xl font-extrabold text-gray-900">
                        Total: 
                        <span class="text-indigo-600 ml-2">
                            ${{ number_format($presupuesto->detalles->sum(fn($d) => $d->cantidad * $d->precio_unitario), 2) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>