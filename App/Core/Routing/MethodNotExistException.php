<?php

namespace Albet\Asmvc\Core\Routing;

class MethodNotExistException extends \Exception
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
