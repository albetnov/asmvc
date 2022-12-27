<?php

namespace App\Asmvc\Core\Console;

use App\Asmvc\Core\Exceptions\DetailableException;

class ComposerInstallFailedException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Looks like composer install failed.");
    }

    public function getDetail(): string
    {
        return "Composer Install should make a 'vendor/' directory. But unfortunely, we can't detect them.";
    }
}
