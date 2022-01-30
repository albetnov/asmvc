<?php

require_once __DIR__ . '/../App/Core/init.php';

use Albet\Asmvc\Core\Route;

csrf()->generateCsrf();
define('BS5_CSS', '');
define('BS5_JS', '');

/**
 * Calling your route
 */
require_once __DIR__ . '/../App/Router/url.php';
Route::triggerRouter();
