use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;

// PAGINA PRINCIPAL
Route::get('/', function () {
    return view('welcome');
});

//DASHBOARD (REQUIERE AUTENTICACION Y VERIFICACION))
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

//RUTAS PROTEGIDAS POR AUTENTICACION 
Route::middleware('auth')->group(function () {

    // PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD DE CLIENTES
    Route::resource('clientes', ClienteController::class);

    // PRESUPUESTOS
    Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
    Route::get('/presupuestos/create', [PresupuestoController::class, 'create'])->name('presupuestos.create');
    Route::post('/presupuestos', [PresupuestoController::class, 'store'])->name('presupuestos.store');
});

require __DIR__.'/auth.php';
