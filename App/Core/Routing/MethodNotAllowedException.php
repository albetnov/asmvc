<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class MethodNotAllowedException extends DetailableException
{
    public function __construct(string|int $method)
    {
        $message = "HTTP $method is not allowed for this route";
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "Please fix your http method request.";
    }
}
