<?php

require_once __DIR__ . '/../App/Core/init.php';

use Albet\Asmvc\Router\Router;

csrf()->generateCsrf();
define('BS5_CSS', 'css/bootstrap.min.css');
define('BS5_JS', 'js/bootstrap.min.js');
$router = new Router;
$router->defineRouter();
