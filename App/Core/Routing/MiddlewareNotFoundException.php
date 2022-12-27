<?php

namespace App\Asmvc\Core\Routing;

class MiddlewareNotFoundException extends \Exception
{
    public function __construct(string $middleware)
    {
        parent::__construct("Middleware: $middleware not found.");
    }
}
