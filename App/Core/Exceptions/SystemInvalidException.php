<?php

namespace Albet\Asmvc\Core\Exceptions;

class SystemInvalidException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("System function is disabled.");
    }

    public function getDetail(): string
    {
        return "Please enable system() in your php.ini file.";
    }
}
