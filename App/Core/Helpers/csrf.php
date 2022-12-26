<?php

use Albet\Asmvc\Core\CsrfGenerator;

if (!function_exists('csrf')) {
    /**
     * Function to access CsrfGenerator Class immediately.
     * @return CsrfGenerator
     */
    function csrf(): CsrfGenerator
    {
        return new CsrfGenerator;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Function to access CsrfGenerator's field method immediately.
     * @return string
     */
    function csrf_field(?string $route = null): string
    {
        return (new CsrfGenerator)->field($route);
    }
}
