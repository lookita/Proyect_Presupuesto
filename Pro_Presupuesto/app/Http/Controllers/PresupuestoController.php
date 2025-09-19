<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Services\PresupuestoService; // Día 14: servicio compartido para cálculo de total

class PresupuestoController extends Controller
{
    /**
     * Día 10: eager loading de cliente y productos en detalles
     * Día 12: filtro por estado en presupuestos
     */
    public function index(Request $request)
    {
        $query = Presupuesto::with(['cliente', 'detalles.producto']); // Día 10: eager loading

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado); // Día 12: filtro por estado
        }

        $presupuestos = $query->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('presupuestos.create', compact('clientes', 'productos'));
    }

    /**
     * Día 8: cálculo de total y uso de addItem() para lógica flexible
     * Día 14: uso de PresupuestoService para cálculo reutilizable
     */
    public function store(Request $request, PresupuestoService $presupuestoService)
    {
        $presupuesto = Presupuesto::create([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'estado' => 'pendiente',
        ]);

        foreach ($request->detalles as $detalle) {
            $presupuesto->addItem(
                $detalle['producto_id'],
                $detalle['cantidad'],
                $detalle['precio_unitario'],
                $detalle['descuento_aplicado'] ?? 0
            );
        }

        $total = $presupuestoService->calcularTotal($presupuesto); // Día 14: lógica delegada al servicio
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

    public function update(Request $request, Presupuesto $presupuesto)
    {
        // Validaciones Día 14
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha'      => 'required|date',
            'total'      => 'required|numeric|min:0',
        ]);

        $presupuesto->update($validated);

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto actualizado correctamente.');
    }
}
