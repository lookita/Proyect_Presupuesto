<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'precio', 'codigo']; 
    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }
}
