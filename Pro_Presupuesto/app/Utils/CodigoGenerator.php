<?php

namespace App\Utils;

use Illuminate\Support\Str;

class CodigoGenerator
{
    /**
     * Genera un código único con prefijo y longitud definida.
     * Ejemplo: CLI-AB12CD
     *
     * @param string $prefijo Prefijo del código (ej. 'CLI')
     * @param int $longitud Número de caracteres aleatorios
     * @return string Código generado, ej: CLI-AB12CD
     */
    public static function generar(string $prefijo = 'GEN', int $longitud = 6): string
    {
        // Se genera una cadena aleatoria en mayúsculas
        $segmento = strtoupper(Str::random($longitud));

        // Se concatena el prefijo con el segmento
        return $prefijo . '-' . $segmento;
    }
}
