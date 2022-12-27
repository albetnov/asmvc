<?php

namespace App\Asmvc\Core\Logger;

use App\Asmvc\Core\Exceptions\DetailableException;

class LogNotBootedException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Initiate of Logger is required");
    }

    public function getDetail(): string
    {
        return "Either run Logger::make() or make sure 'core/bootstrap.php' is running and contain Logger::make() line in it.";
    }
}
