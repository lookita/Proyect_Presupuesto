<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Presupuesto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el panel de control de la aplicación.
     */
    public function index()
    {
        // Obtiene el conteo total de clientes
        $totalClientes = Cliente::count();

        // Obtiene el conteo total de presupuestos
        $totalPresupuestos = Presupuesto::count();

        // Obtiene el total de presupuestos facturados
        $totalFacturado = Presupuesto::where('estado', 'facturado')->sum('total');

        // Obtiene el conteo de presupuestos pendientes (no facturados)
        $presupuestosPendientes = Presupuesto::where('estado', 'no facturado')->count();

        // Obtiene los 5 últimos presupuestos creados para la sección de actividad reciente
        $ultimosPresupuestos = Presupuesto::with('cliente')
                                          ->latest()
                                          ->limit(5)
                                          ->get();

        return view('dashboard', compact(
            'totalClientes',
            'totalPresupuestos',
            'totalFacturado',
            'presupuestosPendientes',
            'ultimosPresupuestos'
        ));
    }
}