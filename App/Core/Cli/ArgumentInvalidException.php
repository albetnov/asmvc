<?php

namespace App\Asmvc\Core\Cli;

use App\Asmvc\Core\Exceptions\DetailableException;

class ArgumentInvalidException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Argument invalid or not found");
    }

    public function getDetail(): string
    {
        return "Please consult php asmvc help!";
    }
}
