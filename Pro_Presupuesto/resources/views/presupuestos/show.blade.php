@extends('layouts.app')
<!--esto si ha que cambiar el html haganlo, pero ya la partes de ruta esta hecho-->
@section('content') 
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Presupuesto #{{ $presupuesto->id }}</h1>

    <p><strong>Cliente:</strong> {{ $presupuesto->cliente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ $presupuesto->fecha }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($presupuesto->estado) }}</p>

    <h2 class="text-lg font-semibold mt-4">Detalles</h2>
    <table class="w-full border mt-2">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Producto</th>
                <th class="p-2">Cantidad</th>
                <th class="p-2">Precio</th>
                <th class="p-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuesto->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ $detalle->precio_unitario }}</td>
                <td>${{ $detalle->cantidad * $detalle->precio_unitario }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right mt-4 text-xl font-bold">
        Total: ${{ $presupuesto->detalles->sum(fn($d) => $d->cantidad * $d->precio_unitario) }}
    </p>
</div>
@endsection
