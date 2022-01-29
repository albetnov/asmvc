<?php

/**
 * A Simple PHP MVC
 * Created by: Albet Novendo
 * File: ASMVC CLI
 * ASMVC is protected by MIT.
 * Contribution: https://github.com/albetnov/simple-php-mvc
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
