@extends('layouts.app')

@section('title', 'Gestión de Presupuestos')

@section('content')
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Sistema de Gestión de Presupuestos</h1>

        <div class="flex border-b border-gray-200">
            <a href="{{ route('presupuestos.index') }}" class="py-2 px-4 text-center border-b-2 border-transparent hover:text-blue-600 hover:border-blue-600 focus:outline-none @if(request()->routeIs('presupuestos.index')) text-blue-600 border-blue-600 @else text-gray-500 @endif">
                Historial de Presupuestos
            </a>
            <a href="{{ route('presupuestos.create') }}" class="py-2 px-4 text-center border-b-2 border-transparent hover:text-blue-600 hover:border-blue-600 focus:outline-none @if(request()->routeIs('presupuestos.create')) text-blue-600 border-blue-600 @else text-gray-500 @endif">
                Crear Nuevo Presupuesto
            </a>
        </div>

        <div class="mt-8">
            @if(request()->routeIs('presupuestos.index'))
                {{-- Contenido de la pestaña "Historial de Presupuestos" --}}
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner">

                    <!-- Día 12: dropdown para filtrar por estado -->
                    <form method="GET" action="{{ route('presupuestos.index') }}" class="mb-6">
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por estado:</label>
                        <select name="estado" id="estado" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Todos --</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="facturado" {{ request('estado') == 'facturado' ? 'selected' : '' }}>Facturado</option>
                        </select>
                    </form>

                    @if(count($presupuestos) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cliente
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
                                    @foreach($presupuestos as $presupuesto)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $presupuesto->cliente->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                ${{ number_format($presupuesto->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button 
                                                    data-id="{{ $presupuesto->id }}" 
                                                    data-status="{{ $presupuesto->estado }}" 
                                                    class="btn-status px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($presupuesto->estado == 'facturado') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                    {{ $presupuesto->estado }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">
                            No hay presupuestos registrados.
                        </p>
                    @endif
                </div>
            @elseif(request()->routeIs('presupuestos.create'))
                {{-- Contenido de la pestaña "Crear Nuevo Presupuesto" --}}
                @include('presupuestos.create')
            @endif
        </div>
    </div>
@endsection
