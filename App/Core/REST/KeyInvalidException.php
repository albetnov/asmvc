<?php

namespace App\Asmvc\Core\REST;

use App\Asmvc\Core\Exceptions\DetailableException;

class KeyInvalidException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Your APP_KEY is invalid");
    }

    public function getDetail(): string
    {
        return "Please check your APP_KEY in '.env' file. Ensure it's exist and has a value.";
    }
}
