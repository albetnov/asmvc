<?php

use App\Asmvc\Core\Requests;
use App\Asmvc\Core\REST\Request;
use App\Asmvc\Core\REST\Rest;

use function DI\autowire;

return [
    'CheckPerformance' => true,
    Requests::class => autowire(),
    Rest::class => autowire(),
    Request::class => autowire(),
];
