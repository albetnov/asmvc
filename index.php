<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/App/Core/Helpers.php';

use Albet\Ppob\Router\Router;

csrf()->generateCsrf();
define('BS5_CSS', 'css/bootstrap.min.css');
define('BS5_JS', 'css/bootstrap.min.js');
$router = new Router;
$router->defineRouter();