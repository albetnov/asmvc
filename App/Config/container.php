<?php

use Albet\Asmvc\Core\Requests;

use function DI\autowire;

return [
    'CheckPerformance' => true,
    Requests::class => autowire(),
];
