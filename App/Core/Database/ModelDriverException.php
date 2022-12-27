<?php

namespace App\Asmvc\Core\Database;

use App\Asmvc\Core\Exceptions\DetailableException;

class ModelDriverException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Your ENV model driver is incompitable with ASMVC.");
    }

    public function getDetail(): string
    {
        return 'Actually, using Eloquent Driver with ASMVC model will just works fine. Remembering that
        ASMVC no need specific configuration to run. However, you may experience wrong model being
        generated. With that being said, It is recommended to set your \'APP_MODELS_DRIVER\' to asmvc.';
    }
}
