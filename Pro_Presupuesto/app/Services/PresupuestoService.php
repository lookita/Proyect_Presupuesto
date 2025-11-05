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
        // Se asume que el Presupuesto ya tiene cargada la relación 'detalles' o se usa 'fresh()' en el controlador.
        // Sumamos la columna 'subtotal' de los detalles, que ya fueron calculados y guardados.
        $subtotalCalculado = $presupuesto->detalles->sum('subtotal');

        // Lógica de impuestos/IVA (se deja simple sin impuestos por ahora)
        $totalFinal = $subtotalCalculado;

        return [
            'subtotal' => round($subtotalCalculado, 2),
            'total' => round($totalFinal, 2)
        ];
    }
}