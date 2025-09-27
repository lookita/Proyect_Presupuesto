<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold text-gray-800">Sistema de Gestión de Presupuestos</h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- --- Pestañas de Navegación --- --}}
                <div class="flex border-b border-gray-200 mb-8">
                    <a href="{{ route('presupuestos.index') }}" class="py-2 px-4 text-center border-b-2 hover:text-indigo-600 hover:border-indigo-600 focus:outline-none {{ request()->routeIs('presupuestos.index') ? 'text-indigo-600 border-indigo-600 font-semibold' : 'text-gray-500 border-transparent' }}">
                        Historial de Presupuestos
                    </a>
                    <a href="{{ route('presupuestos.create') }}" class="py-2 px-4 text-center border-b-2 hover:text-indigo-600 hover:border-indigo-600 focus:outline-none {{ request()->routeIs('presupuestos.create') ? 'text-indigo-600 border-indigo-600 font-semibold' : 'text-gray-500 border-transparent' }}">
                        Crear Nuevo Presupuesto
                    </a>
                </div>
                {{-- -------------------------------- --}}

                @if(request()->routeIs('presupuestos.index'))
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">

                        {{-- --- Dropdown para Filtrar por Estado (Mejorado con Tailwind) --- --}}
                        <form method="GET" action="{{ route('presupuestos.index') }}" class="mb-6 flex items-center space-x-4">
                            <label for="estado" class="text-base font-medium text-gray-700">Filtrar por estado:</label>
                            
                            <select 
                                name="estado" 
                                id="estado" 
                                onchange="this.form.submit()" 
                                class="
                                    p-2.5 
                                    text-base text-gray-800 
                                    border border-gray-300 
                                    rounded-md 
                                    bg-white 
                                    focus:ring-indigo-500 
                                    focus:border-indigo-500 
                                    shadow-sm 
                                    transition duration-150
                                "
                            >
                                <option value="" {{ request('estado') == '' ? 'selected' : '' }}>-- Todos --</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="facturado" {{ request('estado') == 'facturado' ? 'selected' : '' }}>Facturado</option>
                                {{-- Si tienes más estados, agrégalos aquí --}}
                            </select>
                        </form>
                        {{-- ---------------------------------------------------------------- --}}

                        @if(count($presupuestos) > 0)
                            <div class="overflow-x-auto mt-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Cliente
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Total
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Estado
                                            </th>
                                            {{-- Columna de Acciones --}}
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($presupuestos as $presupuesto)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $presupuesto->cliente->nombre }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                                    ${{ number_format($presupuesto->total, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{-- Etiqueta de estado dinámico --}}
                                                    <span 
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $presupuesto->estado == 'facturado' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $presupuesto->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $presupuesto->estado == 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                                                            {{-- Agrega más estados si es necesario --}}
                                                        ">
                                                        {{ ucfirst($presupuesto->estado) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('presupuestos.show', $presupuesto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                                    @can('update', $presupuesto)
                                                    <a href="{{ route('presupuestos.edit', $presupuesto) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Agrega la paginación si tus presupuestos están paginados --}}
                            @if(method_exists($presupuestos, 'links'))
                                <div class="mt-6">
                                    {{ $presupuestos->links() }}
                                </div>
                            @endif

                        @else
                            <p class="text-center text-gray-500 py-8 text-lg">
                                No hay presupuestos registrados que coincidan con el filtro.
                                <a href="{{ route('presupuestos.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium ml-2">Limpiar filtro</a>
                            </p>
                        @endif
                    </div>
                @elseif(request()->routeIs('presupuestos.create'))
                    {{-- Contenido de la pestaña "Crear Nuevo Presupuesto" --}}
                    {{-- Usar 'presupuestos.form' si es un formulario parcial --}}
                    @include('presupuestos.create') 
                @endif
            </div>
        </div>
    </div>
</x-app-layout>