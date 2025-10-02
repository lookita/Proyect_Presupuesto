<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Producto extends Model
{
    use HasFactory;

    // Se incluye 'stock' para permitir carga masiva y se mantiene 'precio'
    protected $fillable = ['nombre', 'precio', 'codigo', 'stock'];

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
            $query->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('codigo', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
