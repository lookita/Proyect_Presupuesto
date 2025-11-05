<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoStoreRequest; // Día 13: validación centralizada para creación
use App\Http\Requests\ProductoUpdateRequest; // Día 13: validación centralizada para edición
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos con funcionalidad de búsqueda.
     * Día 15: Implementación de búsqueda por 'nombre'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $search = $request->get('search', '');

        $productos = Producto::when($search, function ($query) use ($search) {
            // Filtra por el campo 'nombre' que contenga el término de búsqueda
            $query->where('nombre', 'like', '%' . $search . '%');
        })
        ->orderBy('nombre') // Ordena alfabéticamente
        ->paginate(10); // Paginación

        return view('productos.index', [
            'productos' => $productos,
            'search' => $search,
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('productos.create');
    }

    /**
     * Almacena un producto recién creado.
     * Día 13: Uso de ProductoStoreRequest para validación centralizada.
     *
     * @param  \App\Http\Requests\ProductoStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductoStoreRequest $request): RedirectResponse
    {
        // La validación se realiza automáticamente por ProductoStoreRequest
        Producto::create($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    /**
     * Muestra los detalles del producto especificado.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\View\View
     */
    public function show(Producto $producto): View
    {
        // Laravel automáticamente inyecta y busca el Producto por su ID
        // gracias a Route Model Binding (Producto $producto).
        
        return view('productos.show', [
            'producto' => $producto,
        ]);
    }

    /**
     * Muestra el formulario para editar un producto existente.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\View\View
     */
    public function edit(Producto $producto): View
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualiza el producto especificado.
     * Día 13: Uso de ProductoUpdateRequest para validación centralizada.
     *
     * @param  \App\Http\Requests\ProductoUpdateRequest  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductoUpdateRequest $request, Producto $producto): RedirectResponse
    {
        // La validación se realiza automáticamente por ProductoUpdateRequest
        $producto->update($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    /**
     * Elimina el producto especificado.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }

    public function json()
    {
        return response()->json(Producto::all());
    }  
}
