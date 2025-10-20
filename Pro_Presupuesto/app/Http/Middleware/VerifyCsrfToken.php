<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Rutas que deben quedar exentas de CSRF.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}