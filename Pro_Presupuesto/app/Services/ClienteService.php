<?php

namespace App\Services;

use App\Models\Cliente;
use App\Utils\CodigoGenerator;

class ClienteService
{
    /**
     * Día 19: generación de código único para cliente usando clase utilitaria.
     * Se integra CodigoGenerator para separar la lógica de formato.
     * Se valida unicidad con do...while para evitar colisiones.
     *
     * @return string Código único generado para el cliente.
     */
    public function generarCodigo(): string
    {
        do {
            // Genera un código con prefijo 'CL' y 6 caracteres únicos
            $codigo = CodigoGenerator::generar('CL', 6);
        } while (Cliente::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
