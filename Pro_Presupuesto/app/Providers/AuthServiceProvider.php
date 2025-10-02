<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Producto;
use App\Policies\ProductoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Las políticas de autorización del modelo.
     */
    protected $policies = [
        Producto::class => ProductoPolicy::class,
    ];

    /**
     * Registra cualquier servicio de autenticación/autorización.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Opcional: definir gates personalizados si los necesitás
        // Gate::define('admin-only', fn ($user) => $user->role === 'admin');
    }
}
