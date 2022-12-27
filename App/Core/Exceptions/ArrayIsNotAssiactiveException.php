<?php

namespace Albet\Asmvc\Core\Exceptions;

class ArrayIsNotAssiactiveException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Array must be assosiactive array.");
    }

    public function getDetail(): string
    {
        return "Please re-define your array to something like: ['key' => 'pair'].";
    }
}
