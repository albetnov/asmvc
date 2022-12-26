<?php

namespace Albet\Asmvc\Core\Eloquent;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class ModelDriverException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Your ENV model driver is incompitable with Eloquent.");
    }

    public function getDetail(): string
    {
        return 'Using Eloquent Model with ASMVC Driver is not gonna work. 
        Instead change your \'APP_MODELS_DRIVER\' to eloquent in your \'.env\' file.';
    }
}
