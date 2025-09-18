<?php

namespace App\Services;

use App\Models\Cliente;

class ClienteService
{
    /**
     * Día 14: generación de código único para cliente
     */
    public function generarCodigo(): string
    {
        $ultimoId = Cliente::max('id') ?? 0;
        return 'CL-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);
    }
}
