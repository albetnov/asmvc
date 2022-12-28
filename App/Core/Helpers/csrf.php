<?php

use App\Asmvc\Core\CsrfGenerator;

if (!function_exists('csrf')) {
    /**
     * Function to access CsrfGenerator Class immediately.
     */
    function csrf(): CsrfGenerator
    {
        return new CsrfGenerator;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Function to access CsrfGenerator's field method immediately.
     */
    function csrf_field(?string $route = null): string
    {
        return (new CsrfGenerator)->field($route);
    }
}
