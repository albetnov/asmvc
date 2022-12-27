<?php

namespace Albet\Asmvc\Core\Seeders;

use Albet\Asmvc\Core\Exceptions\DetailableException;

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
