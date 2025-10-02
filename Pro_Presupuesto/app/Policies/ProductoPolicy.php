<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    /**
     * Permite ver la lista de productos.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos los usuarios pueden ver la lista
    }

    /**
     * Permite ver un producto específico.
     */
    public function view(User $user, Producto $producto): bool
    {
        return true; // Todos los usuarios pueden ver productos
    }

    /**
     * Permite crear productos.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Permite editar productos.
     */
    public function update(User $user, Producto $producto): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Permite eliminar productos.
     */
    public function delete(User $user, Producto $producto): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Permite restaurar productos eliminados (si usás soft deletes).
     */
    public function restore(User $user, Producto $producto): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Permite eliminar productos permanentemente.
     */
    public function forceDelete(User $user, Producto $producto): bool
    {
        return $user->role === 'admin';
    }
}
