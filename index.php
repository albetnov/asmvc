<?php

require_once __DIR__ . '/App/Core/init.php';

use Albet\Asmvc\Router\Router;

csrf()->generateCsrf();
define('BS5_CSS', '');
define('BS5_JS', '');
$router = new Router;
$router->defineRouter();
