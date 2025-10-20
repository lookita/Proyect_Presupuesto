<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Cookies que no se deben encriptar.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}