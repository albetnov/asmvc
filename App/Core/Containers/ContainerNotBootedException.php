<?php

namespace App\Asmvc\Core\Containers;

use App\Asmvc\Core\Exceptions\DetailableException;

class ContainerNotBootedException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Container not started.");
    }

    public function getDetail(): string
    {
        return 'It\'s look like your container has not been started yet. Please make sure \'bootstrap.php\' run. And it\'s contain calls
        to Container::make().';
    }
}
