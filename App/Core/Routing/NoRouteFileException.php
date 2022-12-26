<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class NoRouteFileException extends DetailableException
{
    public function __construct(?string $message = "Route file not found.")
    {
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "You must specify a routing file, which should located in Routes/routes.php.";
    }
}
