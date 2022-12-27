<?php

namespace App\Asmvc\Core\Database;

class QueryBuilderException extends \Exception
{
    public function __construct(string $query, string $message)
    {
        parent::__construct("Usage of $query error: $message");
    }
}
