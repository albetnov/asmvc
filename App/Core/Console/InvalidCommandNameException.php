<?php

namespace App\Asmvc\Core\Console;

use App\Asmvc\Core\Exceptions\DetailableException;

class InvalidCommandNameException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Your command name is invalid");
    }

    public function getDetail(): string
    {
        return "Please fill \$name property on your command file.";
    }
}
