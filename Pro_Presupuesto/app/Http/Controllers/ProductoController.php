namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoStoreRequest; // Día 13: validación centralizada para creación
use App\Http\Requests\ProductoUpdateRequest; // Día 13: validación centralizada para edición

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    /**
     * Día 13: validación centralizada con ProductoStoreRequest
     */
    public function store(ProductoStoreRequest $request)
    {
        Producto::create($request->validated());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Día 13: validación centralizada con ProductoUpdateRequest
     */
    public function update(ProductoUpdateRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
