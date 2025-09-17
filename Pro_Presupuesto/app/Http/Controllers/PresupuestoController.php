<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;

class PresupuestoController extends Controller
{
    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('presupuestos.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $presupuesto = Presupuesto::create([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'estado' => 'pendiente',
        ]);

        $total = 0;

        foreach ($request->detalles as $detalle) {
            $item = $presupuesto->addItem(
                $detalle['producto_id'],
                $detalle['cantidad'],
                $detalle['precio_unitario'],
                $detalle['descuento_aplicado'] ?? 0
            );

            $total += $item->subtotal;
        }

        $presupuesto->update(['total' => $total]);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado correctamente.');
    }
}
