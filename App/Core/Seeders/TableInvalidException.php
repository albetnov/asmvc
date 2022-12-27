<?php

namespace Albet\Asmvc\Core\Seeders;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class TableInvalidException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Table is invalid");
    }

    public function getDetail(): string
    {
        return "Make sure you already call setTable() method.";
    }
}
