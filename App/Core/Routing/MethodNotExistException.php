<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Exceptions\DetailableException;

class MethodNotExistException extends DetailableException
{
    public function __construct(?string $message = "Method of given class not found.")
    {
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "Your controller class is found, but the method not.";
    }
}
