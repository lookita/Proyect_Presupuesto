<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Presupuesto extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'fecha', 'estado'];

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con los detalles del presupuesto
    public function detalles(){
        return $this->hasMany(PresupuestoDetalle::class);
    }

    /**
     * Scope para implementar la lógica de búsqueda por cliente o estado.
     */
    public function scopeSearch(Builder $query, ?string $search): void
    {
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                // 1. Búsqueda directa por estado (asume que el estado es una palabra clave)
                $q->where('estado', 'LIKE', "%{$search}%")
                  // 2. Búsqueda por ID de presupuesto
                  ->orWhere('id', $search)
                  // 3. Búsqueda por nombre o código del Cliente relacionado (JOIN implícito)
                  ->orWhereHas('cliente', function (Builder $qr) use ($search) {
                      $qr->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('codigo', 'LIKE', "%{$search}%");
                  });
            });
        }
    }

    public function addItem($productoId, $cantidad, $precioUnitario, $descuento)
    {
        $precioFinal = $precioUnitario * (1 - $descuento / 100);
        $subtotal = $precioFinal * $cantidad;

        return $this->detalles()->create([
            'producto_id' => $productoId,
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'descuento_aplicado' => $descuento,
            'subtotal' => $subtotal,
        ]);
    }
}
