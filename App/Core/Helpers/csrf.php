<?php

use Albet\Asmvc\Core\CsrfGenerator;

/**
 * Function to access CsrfGenerator Class immediately.
 * @return CsrfGenerator
 */
function csrf()
{
    return new CsrfGenerator;
}

/**
 * Function to access CsrfGenerator's field method immediately.
 * @return CsrfGenerator
 */
function csrf_field($route = null)
{
    return (new CsrfGenerator)->field($route);
}
