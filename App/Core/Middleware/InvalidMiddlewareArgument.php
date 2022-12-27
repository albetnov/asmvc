<?php

namespace Albet\Asmvc\Core\Middleware;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class InvalidMiddlewareArgument extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Middleware invalid.");
    }

    public function getDetail(): string
    {
        return "every middlewares must extends Middleware class provided by 'Asmvc/Core'.";
    }
}
