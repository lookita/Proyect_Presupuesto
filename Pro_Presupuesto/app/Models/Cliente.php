<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // ¡CORRECCIÓN: Necesario para tipar el scope!

class Cliente extends Model
{
    use HasFactory;

    // Se asume protected $table = 'clientes'; por convención de Laravel, se puede omitir.

    protected $fillable = [
        'nombre',
        'codigo',
        'email',
        'cuit',
        'telefono',
        'direccion',
    ];

    /**
     * Scope para la funcionalidad de búsqueda en Clientes (Día 15 - Franco).
     * Permite buscar por nombre, código o email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        // CORRECCIÓN: Se envuelve la lógica en un if para que no falle si $search está vacío
        if ($search) {
            // Se utiliza where/orWhere para buscar coincidencias parciales.
            $query->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('codigo', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%'); // Se añadió búsqueda por email para ser más robustos
        }
        return $query;
    }

    /**
     * Relación: Un cliente puede tener muchos presupuestos.
     */
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }
}
