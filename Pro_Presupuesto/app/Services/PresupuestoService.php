<?php
namespace App\Services;

use App\Models\Presupuesto;
use Illuminate\Support\Collection; // Mantener si es necesario para otros métodos

class PresupuestoService
{
    /**
     * Calcula el subtotal de un ítem con descuento, recibiendo valores individuales.
     * Esta firma resuelve el 'TypeError' al recibir float en lugar de object.
     * * @param float $precio       Precio unitario del producto.
     * @param int $cantidad       Cantidad del producto.
     * @param float $descuento    Porcentaje de descuento (0 a 100).
     * @return float
     */
    public function calcularSubtotal(float $precio, int $cantidad, float $descuento = 0.0): float
    {
        // Asegurar que los valores mínimos sean 0
        $cantidad = max(0, $cantidad);
        $precio = max(0, $precio);
        
        // Asegurar que el descuento esté entre 0 y 100
        $descuento = min(max($descuento, 0), 100);

        $factor = 1 - ($descuento / 100);
        
        return round($cantidad * $precio * $factor, 2);
    }

    /**
     * Calcula los totales finales del Presupuesto sumando los subtotales de los detalles.
     * * @param Presupuesto $presupuesto La instancia del presupuesto (debe haber sido refrescada).
     * @return array
     */
    public function calcularTotalesPresupuesto(Presupuesto $presupuesto): array
    {
        // subtotal bruto = cantidad * precio unitario, sin descuentos
        $subtotalBruto = $presupuesto->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });

        // total neto = suma de subtotales de los detalles (ya con descuentos)
        $totalNeto = $presupuesto->detalles->sum('subtotal');

        return [
            'subtotal' => round($subtotalBruto, 2), // este se guarda como subtotal en DB
            'total' => round($totalNeto, 2)         // este se guarda como total en DB
        ];
    }
}