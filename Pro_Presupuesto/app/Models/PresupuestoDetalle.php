<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    protected $fillable = ['presupuesto_id', 'producto_id', 'cantidad', 'subtotal'];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
