@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
    <div class="container mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Clientes</h1>
            <a href="{{ route('clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Crear Cliente
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (count($clientes) > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cliente->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('clientes.show', $cliente->id) }}" class="text-blue-600 hover:text-blue-900 mx-1">Ver</a>
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">Editar</a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 mx-1" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-lg shadow-lg">
                <p class="text-gray-500">No hay clientes registrados. ¡Comienza a crear uno!</p>
            </div>
        @endif
    </div>
@endsection