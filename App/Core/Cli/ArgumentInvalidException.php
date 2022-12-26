<?php

namespace Albet\Asmvc\Core\Cli;

use Albet\Asmvc\Core\Exceptions\DetailableException;

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
