<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteStoreRequest;
use App\Services\ClienteService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de clientes con búsqueda y orden dinámico.
     */
    public function index(Request $request)
    {
        // Filtros y búsqueda de clientes
    
        $query = Cliente::query();
    
        // Si hay un término de búsqueda, filtramos por nombre o email
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%");
        }
    
        // Si hay filtro por fecha de creación
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->input('fecha_desde'));
        }
    
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->input('fecha_hasta'));
        }
    
        // Paginamos los resultados
        $clientes = $query->orderBy('nombre')->paginate(10);
    
        // Enviamos la data a la vista
        return view('clientes.index', compact('clientes'));
    }
    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create(): View
    {
        return view('clientes.create');
    }

    /**
     * Almacena un nuevo cliente validado.
     */
    public function store(ClienteStoreRequest $request, ClienteService $clienteService): RedirectResponse
    {
        $data = $request->validated();
        $data['codigo'] = $clienteService->generarCodigo();

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Muestra los detalles de un cliente específico.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\View\View
     */
    public function show(Cliente $cliente): View
    {
        // Gracias al Route Model Binding, Laravel ya inyectó el cliente
        // basado en el ID de la ruta.
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Muestra el formulario de edición para un cliente.
     */
    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza un cliente existente.
     */
    public function update(ClienteStoreRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    /**
     * Elimina un cliente.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    //Exporta PDF
    public function exportarPDF(Request $request)
    {
        $query = Cliente::query();

        // Aplicar los mismos filtros que en el index
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%");
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->input('fecha_desde'));
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->input('fecha_hasta'));
        }

        $clientes = $query->orderBy('nombre')->get();

        // Generar el PDF con la vista
        $pdf = Pdf::loadView('clientes.pdf', compact('clientes'));
        return $pdf->download('clientes.pdf');
    }
}
