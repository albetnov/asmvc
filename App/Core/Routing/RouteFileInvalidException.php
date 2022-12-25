<?php

namespace Albet\Asmvc\Core\Routing;

class RouteFileInvalidException extends \Exception
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
