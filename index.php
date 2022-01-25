<?php

require_once __DIR__ . '/App/Core/init.php';

use Albet\Ppob\Router\Router;

csrf()->generateCsrf();
$router = new Router;
$router->defineRouter();
