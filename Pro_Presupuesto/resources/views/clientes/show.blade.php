@extends('layouts.app')

@section('title', 'Detalle del Cliente')

@section('content')
    <div class="container mx-auto p-8">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold mb-4 text-gray-800">Detalle del Cliente</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Nombre</p>
                    <p class="text-xl text-gray-900">{{ $cliente->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Código</p>
                    <p class="text-xl text-gray-900">{{ $cliente->codigo }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Email</p>
                    <p class="text-xl text-gray-900">{{ $cliente->email }}</p>
                </div>
            </div>

            <hr class="my-6 border-gray-300">

            <h2 class="text-2xl font-bold mb-4 text-gray-800">Presupuestos Asociados</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200">
                        <tr>
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
                        @if($cliente->presupuestos->isEmpty())
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">
                                    Este cliente no tiene presupuestos.
                                </td>
                            </tr>
                        @else
                            @foreach($cliente->presupuestos as $presupuesto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $presupuesto->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${{ number_format($presupuesto->total, 2) }}
                                    </td>
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
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('clientes.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none">
                    Volver al listado
                </a>
            </div>
        </div>
    </div>
@endsection