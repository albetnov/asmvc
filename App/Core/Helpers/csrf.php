<?php

use Albet\Asmvc\Core\CsrfGenerator;

/**
 * Function to access CsrfGenerator Class immediately.
 * @return CsrfGenerator
 */
function csrf(): CsrfGenerator
{
    return new CsrfGenerator;
}

/**
 * Function to access CsrfGenerator's field method immediately.
 * @return string
 */
function csrf_field(?string $route = null): string
{
    return (new CsrfGenerator)->field($route);
}
