<?php

namespace App\Asmvc\Core\Seeders;

use App\Asmvc\Core\Exceptions\DetailableException;

class PayloadInvalidException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Payload is invalid");
    }

    public function getDetail(): string
    {
        return "Make sure you have using either fake() method or addPayload() or payload(). With associative array as their arguments.";
    }
}
