<?php

namespace App\Services;

use App\Models\Presupuesto;

class PresupuestoService
{
    /**
     * Día 14: lógica compartida para presupuestos
     */
    public function calcularTotal(Presupuesto $presupuesto): float
    {
        return $presupuesto->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario * (1 - ($detalle->descuento_aplicado ?? 0) / 100);
        });
    }
}
