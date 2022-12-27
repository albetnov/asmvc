<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Exceptions\DetailableException;

class RouteFileInvalidException extends DetailableException
{
    public function __construct(?string $message = "Route file is invalid.")
    {
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "Your route file must return anonymous function!";
    }
}
