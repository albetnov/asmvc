<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Exceptions\DetailableException;

class ControllerNotFoundException extends DetailableException
{
    public function __construct(?string $message = "Controller class not found.")
    {
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "Your controller class is not found. Please check their autoloading conventions.";
    }
}
