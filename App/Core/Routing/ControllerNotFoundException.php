<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\Exceptions\DetailableException;

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
