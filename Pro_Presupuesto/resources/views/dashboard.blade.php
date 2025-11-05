<x-app-layout>
    <div class="container mx-auto p-8 pt-4">
        <div class="flex items-center justify-between bg-transparent">
            <div x-data="{ openHelp: false }" class="flex items-center space-x-2">
                <button @click="openHelp = true" class="inline-flex items-center px-3 py-1 border rounded bg-white hover:bg-gray-50 text-sm">
                    ❔ Ayuda
                </button>

                <!-- Modal -->
                <div x-show="openHelp" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                    <div @click.away="openHelp = false" @keydown.window.escape="openHelp = false" class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-center">Guía de Uso del Sistema</h2>
                            <button @click="openHelp = false" class="text-gray-500 hover:text-gray-700">✕</button>
                        </div>

                        <div class="prose max-w-none text-sm text-gray-700">
                            @include('dashboard_instructions')
                        </div>

                        <div class="mt-6 flex justify-end gap-2">
                            <button @click="openHelp = false" class="px-3 py-2 bg-blue-600 text-white rounded">Cerrar</button>
                        </div>
                    </div>
                </div>
                <!-- /Modal -->
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Clientes</h2>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalClientes }}</p>
                    </div>
                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-10a4 4 0 01-4-4V7a4 4 0 014-4h10a4 4 0 014 4v9a4 4 0 01-4 4zm-5-3.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM12 9a2 2 0 10-4 0v4h4V9z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Presupuestos</h2>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalPresupuestos }}</p>
                    </div>
                    <div class="bg-purple-100 text-purple-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-3 0V3m0 2a2 2 0 002 2h2a2 2 0 002-2m-3 0V3m0 2a2 2 0 002 2h2a2 2 0 002-2zM9 13h6M9 17h6"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Facturado</h2>
                        <p class="mt-1 text-3xl font-bold text-green-600">${{ number_format($totalFacturado, 2) }}</p>
                    </div>
                    <div class="bg-green-100 text-green-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.14 0-5.7 2.37-6.2 5.46M12 8a5.5 5.5 0 00-5.5 5.5M12 8a5.5 5.5 0 015.5 5.5M12 8h.01M16.5 12h.01M7.5 12h.01M12 12a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Presupuestos Pendientes</h2>
                        <p class="mt-1 text-3xl font-bold text-red-600">{{ $presupuestosPendientes }}</p>
                    </div>
                    <div class="bg-red-100 text-red-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.678 0 2.518-2.035 1.339-3.376L13.339 4.376a2.035 2.035 0 00-3.678 0L3.714 15.624C2.535 16.965 3.375 19 5.053 19z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Últimos Presupuestos Creados</h2>

                @if ($ultimosPresupuestos->isEmpty())
                <div class="text-center py-4 text-gray-500 italic">
                    <p>No hay presupuestos recientes para mostrar.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ultimosPresupuestos as $presupuesto)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $presupuesto->cliente->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $presupuesto->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($presupuesto->total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($presupuesto->estado === 'facturado')
                                            bg-green-100 text-green-800
                                        @elseif($presupuesto->estado === 'pendiente')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        {{ $presupuesto->estado }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
</x-app-layout>