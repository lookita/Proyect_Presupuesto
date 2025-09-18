use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteStoreRequest; // Día 13: uso de FormRequest para validaciones centralizadas

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
     */
    public function store(ClienteStoreRequest $request)
    {
        // Generar código único basado en el último ID
        $ultimoId = Cliente::max('id') ?? 0;
        $codigoGenerado = 'CL-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

        // Combinar datos validados con el código generado
        $data = $request->validated();
        $data['codigo'] = $codigoGenerado;

        // Crear cliente
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
    public function update(ClienteStoreRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}
