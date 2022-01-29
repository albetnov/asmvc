<?php

/**
 * ASMVC by Albet Novendo.
 * This file is your apps entry point. Every request are redirected to here.
 */

// Call the autoload.
require_once __DIR__ . '/../App/Core/init.php';

// Use the routing defined by user.
use Albet\Asmvc\Router\Router;

csrf()->generateCsrf();
define('BS5_CSS', '');
define('BS5_JS', '');
$router = new Router;
$router->defineRouter();
