<?php

use App\Asmvc\Core\Requests;

use function DI\autowire;

return [
    'CheckPerformance' => true,
    Requests::class => autowire(),
];
