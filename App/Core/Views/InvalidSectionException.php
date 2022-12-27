<?php

namespace App\Asmvc\Core\Views;

use App\Asmvc\Core\Exceptions\DetailableException;

class InvalidSectionException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Section not detected!");
    }

    public function getDetail(): string
    {
        return "It's odd to end a section that you never define.";
    }
}
