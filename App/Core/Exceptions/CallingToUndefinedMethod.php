<?php

namespace Albet\Asmvc\Core\Exceptions;

class CallingToUndefinedMethod extends \Exception
{
    public function __construct(string $method)
    {
        parent::__construct("Calling to undefined $method method.");
    }
}
