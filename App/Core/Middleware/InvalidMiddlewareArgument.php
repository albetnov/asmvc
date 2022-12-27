<?php

namespace App\Asmvc\Core\Middleware;

use App\Asmvc\Core\Exceptions\DetailableException;

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
