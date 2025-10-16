<x-app-layout>
    <div class="container mx-auto p-8">
          <!-- Inicio de la guía de uso del sistema -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h1 class="text-2xl font-bold mb-4 text-center">Guía de Uso del Sistema</h1>
            <p class="mb-4 text-gray-700">
                Bienvenido al sistema de gestión de presupuestos. Aquí podés administrar clientes, productos y presupuestos de forma sencilla.
            </p>
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                <li><strong>Clientes:</strong> Crear, editar o eliminar clientes.</li>
                <li><strong>Productos:</strong> Gestionar el catálogo de productos con precios actualizados.</li>
                <li><strong>Presupuestos:</strong> Generar presupuestos seleccionando un cliente y los productos.</li>
                <li><strong>Exportar:</strong> Descargar presupuestos en PDF.</li>
                <li><strong>Buscar:</strong> Usar filtros para encontrar clientes o presupuestos fácilmente.</li>
            </ul>
        </div>
        <!-- fin de la guia del sistema -->

        <!-- Simulación del flujo real -->
        <div class="bg-gray-50 p-6 rounded-lg border mb-8">
            <h2 class="text-xl font-semibold mb-3">Simulación del flujo real</h2>
            <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                <li>Ingresar como <strong>Administrador</strong> y cargar varios clientes y productos.</li>
                <li>Crear un nuevo presupuesto seleccionando un cliente y varios productos.</li>
                <li>Exportar el presupuesto generado en formato PDF.</li>
                <li>Ingresar como <strong>Usuario</strong> y verificar que no pueda editar ni eliminar.</li>
                <li>Usar el buscador para filtrar clientes o presupuestos.</li>
                <li>Verificar que los mensajes de éxito o error aparezcan correctamente.</li>
            </ol>
        </div>
        <!-- Fin del flujo real  -->
        
        <!-- Tarjetas de estadísticas -->
        <div class="bg-white p-6 rounded shadow mt-6">
            <h2 class="text-xl font-bold mb-2">Prueba final del sistema</h2>
            <p>Ingresar como admin: revisar clientes, productos y presupuestos.</p>
            <p>Ingresar como usuario: verificar navegación sin permisos de edición.</p>
        </div>
        <div class="bg-white p-6 rounded shadow mt-6">
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