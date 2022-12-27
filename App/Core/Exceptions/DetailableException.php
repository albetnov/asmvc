<?php

namespace App\Asmvc\Core\Exceptions;

use Exception;

abstract class DetailableException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        if (PHP_SAPI === "cli") {
            echo "Detail: " . $this->getDetail() . PHP_EOL;
        }
    }
    abstract function getDetail(): string;
}
