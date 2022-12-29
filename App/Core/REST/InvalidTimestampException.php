<?php

namespace App\Asmvc\Core\REST;

use App\Asmvc\Core\Exceptions\DetailableException;

class InvalidTimestampException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Timestamp invalid.");
    }

    public function getDetail(): string
    {
        return "Please make sure your timestamp representing Unix Timestamp";
    }
}
