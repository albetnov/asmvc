<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/App/Core/Helpers.php';
use Albet\Ppob\Router\Router;
csrf()->generateCsrf();
$router = new Router;
$router->defineRouter();
