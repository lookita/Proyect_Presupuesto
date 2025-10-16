<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresupuestoDetalle extends Model
{
    use HasFactory;
    
    // Los campos que se pueden llenar masivamente, incluyendo los nuevos campos 
    // calculados en Presupuesto::addItem().
    protected $fillable = [
        'presupuesto_id', 
        'producto_id', 
        'cantidad', 
        'precio_unitario', // Necesario para guardar el precio en el momento de la creación
        'descuento_aplicado', // El descuento aplicado a esta línea
        'subtotal' // El resultado del cálculo (cantidad * precio_unitario * (1-descuento))
    ];
    
    // Relación: Un detalle pertenece a un presupuesto
    public function presupuesto(){
        return $this->belongsTo(Presupuesto::class);
    }

    // Relación: Un detalle está vinculado a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    // Opcional: Si usamos el campo 'id' como referencia para búsqueda en lugar del código, 
    // no se requiere un scopeSearch en el detalle, ya que la búsqueda principal siempre
    // se hará a través del modelo Presupuesto.
}
