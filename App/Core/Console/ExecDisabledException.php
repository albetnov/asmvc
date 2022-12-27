<?php

namespace App\Asmvc\Core\Console;

use App\Asmvc\Core\Exceptions\DetailableException;

class ExecDisabledException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Exec function is disabled");
    }

    public function getDetail(): string
    {
        return "It's look like you disabled exec() function execution in php.ini. Please enable it.";
    }
}
