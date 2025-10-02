<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteStoreRequest;
use App\Services\ClienteService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de clientes con búsqueda y orden dinámico.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $orderBy = $request->get('order_by', 'nombre');
        $direction = $request->get('direction', 'asc');

        $clientes = Cliente::query()
            ->when($search, function ($query) use ($search) {
                $query->search($search); // Usa scopeSearch definido en Cliente.php
            })
            ->orderBy($orderBy, $direction)
            ->paginate(10);

        return view('clientes.index', compact('clientes', 'search'));
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
}
