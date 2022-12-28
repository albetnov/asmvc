<?php

namespace App\Asmvc\Core\Exceptions;

class InvalidHttpCodePageException extends \Exception
{
    public function __construct(int $code, array $whiteListCode)
    {
        $builder = implode(" ", $whiteListCode);
        parent::__construct("Invalid $code. Either set noPages to true or change code to one of: $builder");
    }
}
