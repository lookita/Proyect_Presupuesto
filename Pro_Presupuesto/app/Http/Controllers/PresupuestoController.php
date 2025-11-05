<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\PresupuestoDetalle;
use App\Services\PresupuestoService;
use App\Http\Requests\StorePresupuestoRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PresupuestoController extends Controller
{
    /**
     * Muestra la lista de presupuestos con filtros.
     */
    public function index(Request $request): View
    {
        $presupuestos = Presupuesto::with(['cliente', 'detalles.producto'])
            ->latest()
            ->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    /**
     * Muestra el formulario para crear un nuevo presupuesto.
     */
    public function create(): View
    {
        $clientes = Cliente::all(['id', 'nombre']);
        $productos = Producto::all(['id', 'nombre', 'precio']);
        return view('presupuestos.create', compact('clientes', 'productos'));
    }

    /**
     * Almacena un presupuesto recién creado en la base de datos.
     */
    public function store(StorePresupuestoRequest $request, PresupuestoService $presupuestoService): RedirectResponse
    {
        // La validación se maneja en StorePresupuestoRequest
        $validatedData = $request->validated();

        // Usamos una transacción para asegurar la integridad de Presupuesto y sus Detalles
        return DB::transaction(function () use ($validatedData, $request, $presupuestoService) {

            // 1. Crear el Presupuesto principal
            $presupuesto = Presupuesto::create([
                'cliente_id' => $validatedData['cliente_id'],
                'fecha_emision' => $validatedData['fecha_emision'],
                'estado' => 'pendiente',
                'subtotal' => 0.00,
                'total' => 0.00,
            ]);

            // 2. Guardar los Detalles y calcular el subtotal de cada ítem
            foreach ($request->items as $item) {
                $precioUnitario = (float) $item['precio_unitario'];
                $cantidad = (int) $item['cantidad'];
                $descuento = (float) ($item['descuento'] ?? 0);

                // Delegar el cálculo del subtotal del ítem al servicio 
                $subtotalItem = $presupuestoService->calcularSubtotal($precioUnitario, $cantidad, $descuento);

                $presupuesto->detalles()->create([ // Usamos la relación para asegurar el ID
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento_aplicado' => $descuento,
                    'subtotal' => $subtotalItem,
                ]);
            }

            // 3. Recalcular el Subtotal y Total usando el servicio
            $totales = $presupuestoService->calcularTotalesPresupuesto($presupuesto->fresh());

            // 4. Actualizar el Presupuesto con los totales finales
            $presupuesto->update([
                'subtotal' => $totales['subtotal'],
                'total' => $totales['total'],
            ]);

            return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado correctamente.');
        });
    }

    /**
     * Muestra el presupuesto específico.
     */
    public function show(Presupuesto $presupuesto): View
    {
        // Eager load de las relaciones necesarias
        $presupuesto->load(['cliente', 'detalles.producto']);

        return view('presupuestos.show', compact('presupuesto'));
    }


    /**
     * Muestra el formulario para editar el presupuesto específico.
     */
    public function edit(Presupuesto $presupuesto): View
    {
        // Eager load de las relaciones necesarias
        $presupuesto->load(['detalles.producto']);

        $clientes = Cliente::all(['id', 'nombre']);
        $productos = Producto::all(['id', 'nombre', 'precio']);

        return view('presupuestos.edit', compact('presupuesto', 'clientes', 'productos'));
    }

    /**
     * Actualiza el presupuesto específico en la base de datos.
     */
    public function update(Request $request, Presupuesto $presupuesto, PresupuestoService $presupuestoService): RedirectResponse
    {
        // 1. Validación de cabecera y detalles 
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_emision' => 'required|date',
            'estado' => 'required|in:pendiente,aceptado,rechazado,facturado',

            // Reglas para el array de ítems
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:presupuesto_detalles,id',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'items.*.descuento' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($presupuesto, $validatedData, $request, $presupuestoService) {

            // 2. Actualizar el Presupuesto principal (cabecera)
            $presupuesto->update([
                'cliente_id' => $validatedData['cliente_id'],
                'fecha_emision' => $validatedData['fecha_emision'],
                'estado' => $validatedData['estado'],
            ]);

            // Recolectar IDs de los detalles que deben permanecer
            $requestItemIds = collect($request->items)->pluck('id')->filter()->toArray();

            // 3. Eliminar los detalles que NO están en la solicitud actual (sincronización)
            $presupuesto->detalles()->whereNotIn('id', $requestItemIds)->delete();

            // 4. Iterar sobre los ítems de la solicitud para crear/actualizar
            foreach ($request->items as $item) {
                $precioUnitario = (float) $item['precio_unitario'];
                $cantidad = (int) $item['cantidad'];
                $descuento = (float) ($item['descuento'] ?? 0);

                // CORRECCIÓN: Llamada al método correcto en el Service
                $subtotalItem = $presupuestoService->calcularSubtotal($precioUnitario, $cantidad, $descuento);

                $detalleData = [
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento_aplicado' => $descuento,
                    'subtotal' => $subtotalItem,
                ];

                if (isset($item['id'])) {
                    // Actualizar detalle existente
                    PresupuestoDetalle::where('id', $item['id'])
                        ->where('presupuesto_id', $presupuesto->id)
                        ->update($detalleData);
                } else {
                    // Crear nuevo detalle
                    $presupuesto->detalles()->create($detalleData);
                }
            }

            // 5. Recalcular y actualizar los Totales del Presupuesto
            // fresh() para obtener los detalles recién creados/actualizados
            $totales = $presupuestoService->calcularTotalesPresupuesto($presupuesto->fresh());

            $presupuesto->update([
                'subtotal' => $totales['subtotal'],
                'total' => $totales['total'],
            ]);

            return redirect()->route('presupuestos.show', $presupuesto->id)
                 ->with('success', 'Presupuesto actualizado correctamente.');

        });
    }

    /**
     * Acción PATCH para cambiar estado del presupuesto con validación
     */
    public function actualizarEstado(Request $request, Presupuesto $presupuesto): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aceptado,rechazado,facturado',
        ]);

        $presupuesto->update([
            'estado' => $request->estado,
        ]);

        return redirect()->route('presupuestos.index')->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Elimina el presupuesto.
     */
    public function destroy(Presupuesto $presupuesto): RedirectResponse
    {
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto eliminado correctamente.');
    }

    /**
     * Exporta la lista de presupuestos a PDF.
     */
    public function exportarPDF(Request $request)
    {
        $query = Presupuesto::with('cliente');

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->input('cliente_id'));
        }

        $presupuestos = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('presupuestos.pdf', compact('presupuestos'));

        return $pdf->download('presupuestos_listado.pdf'); // Nombre más descriptivo
    }
}
