<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;

class PresupuestoController extends Controller
{
    /**
     * Día 10: eager loading de cliente y productos en detalles
     */
    public function index()
    {
        $presupuestos = Presupuesto::with(['cliente', 'detalles.producto'])->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('presupuestos.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        // Día 8: cálculo de total y uso de addItem() para lógica flexible
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

    /**
     * Día 9: acción PATCH para cambiar estado del presupuesto con validación
     */
    public function actualizarEstado(Request $request, Presupuesto $presupuesto)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,facturado',
        ]);

        $presupuesto->update([
            'estado' => $request->estado,
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Estado actualizado correctamente.');
    }
}
