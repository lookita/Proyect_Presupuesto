<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PresupuestoDetalle;

class Producto extends Model
{
    use HasFactory;

    // Se incluye 'stock' para permitir carga masiva y se mantiene 'precio'
    protected $fillable = ['nombre', 'precio', 'codigo', 'stock'];

    // Tipado de campos
    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Relación: Un producto puede estar en muchos detalles de presupuesto.
     */
    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }

    /**
     * Scope para búsqueda por nombre o código.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('nombre', 'like', '%'.$search.'%')
                  ->orWhere('codigo', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }
}
