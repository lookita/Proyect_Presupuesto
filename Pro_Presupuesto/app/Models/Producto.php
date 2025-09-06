namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'precio'];

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }
}
