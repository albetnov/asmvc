<?php

namespace Albet\Asmvc\Core\Containers;

use Albet\Asmvc\Core\Exceptions\DetailableException;

class OptimizationRequiredException extends DetailableException
{
    public function __construct()
    {
        parent::__construct("Ups, look like you need to optimize your dependencies.");
    }

    public function getDetail(): string
    {
        return 'ASMVC used PHP-DI with AutoWiring by default. AutoWiring works by scanning through your file. 
        And these action pretty costly. With that being said, you need to optimize it by filling your container.php file. Please
        consult: https://php-di.org/doc/performances.html. This settings can be turned of by setting CheckPerfomance to false in
        container.php';
    }
}
