<?php

namespace Albet\Asmvc\Core\Middleware;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class InvalidMiddlewareArgument extends DetailableException
{
    public function __construct()
    {
        parent::__construct("You haven't set middleware yet.");
    }

    public function getDetail(): string
    {
        return "Please call set() method first with your middleware class in it.";
    }
}
