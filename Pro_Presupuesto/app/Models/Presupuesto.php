<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presupuesto extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'fecha', 'estado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
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
