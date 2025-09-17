@extends('layouts.app')

@section('title', 'Crear Nuevo Presupuesto')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Crear Nuevo Presupuesto</h1>

        <form action="{{ route('presupuestos.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="cliente_id" class="block text-gray-700 font-bold mb-2">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona un cliente...</option>
                    {{-- Los clientes se llenarán desde el controlador en el futuro --}}
                </select>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-bold mb-4">Detalle del Presupuesto</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="py-2 px-4 text-left font-semibold">Producto</th>
                                <th class="py-2 px-4 text-left font-semibold w-24">Cantidad</th>
                                <th class="py-2 px-4 text-right font-semibold w-32">Precio Unitario</th>
                                <th class="py-2 px-4 text-right font-semibold w-32">Subtotal</th>
                                <th class="py-2 px-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody id="productos-container">
                            </tbody>
                    </table>
                </div>

                <button type="button" id="add-product" class="mt-4 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Agregar Producto
                </button>
            </div>

            <div class="mt-8 text-right pr-4">
                <div class="text-gray-700 font-semibold text-lg">
                    Subtotal: <span id="subtotal" class="font-normal">$0.00</span>
                </div>
                <div class="text-gray-700 font-bold text-xl mt-2">
                    Total: <span id="total" class="font-normal">$0.00</span>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Guardar Presupuesto
                </button>
            </div>
        </form>
    </div>
@endsection
