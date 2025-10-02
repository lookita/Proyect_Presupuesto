namespace App\Services;

use Illuminate\Support\Collection;

class PresupuestoService
{
    /**
     * Calcula el total de un presupuesto.
     * Día 19: lógica aislada, validación de campos y manejo de nulos.
     */
    public function calcularTotal(Collection $detalles): float
    {
        return $detalles->sum(fn ($detalle) => $this->calcularSubtotal($detalle));
    }

    /**
     * Calcula el subtotal de un ítem con descuento.
     */
    public function calcularSubtotal(object $detalle): float
    {
        $cantidad = max(0, $detalle->cantidad ?? 0);
        $precio = max(0, $detalle->precio_unitario ?? 0);
        $descuento = min(max($detalle->descuento_aplicado ?? 0, 0), 100);

        $factor = 1 - ($descuento / 100);
        return round($cantidad * $precio * $factor, 2);
    }
}
