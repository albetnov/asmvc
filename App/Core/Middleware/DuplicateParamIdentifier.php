<?php

namespace Albet\Asmvc\Core\Middleware;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class DuplicateParamIdentifier extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Duplicate parameter indentifier.");
    }

    public function getDetail(): string
    {
        return "Please call recheck your addParam() method with same identifier.";
    }
}
