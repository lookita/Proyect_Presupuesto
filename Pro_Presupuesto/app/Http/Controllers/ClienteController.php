<?php

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteStoreRequest; // Día 13: uso de FormRequest para validaciones centralizadas
use App\Services\ClienteService; // Día 14: servicio compartido para generación de código

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Día 13: validación centralizada con ClienteStoreRequest
     * Día 14: uso de ClienteService para generar código único
     */
    public function store(ClienteStoreRequest $request, ClienteService $clienteService) /**Dia 14 de validaciones de store */
    { 
        $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'email'  => 'required|email|unique:clientes,email',
    ]);
        $data = $request->validated();
        $data['codigo'] = $clienteService->generarCodigo(); // Día 14: lógica delegada al servicio

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Día 13: validación centralizada con ClienteStoreRequest
     */
    public function update(ClienteStoreRequest $request, Cliente $cliente) /**dia 14 de validaciones de update, tarea de fran */
    {
        $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'email'  => 'required|email|unique:clientes,email,' . $cliente->id,
    ]);
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');

    }
}
